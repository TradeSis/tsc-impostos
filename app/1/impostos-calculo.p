DEF VAR hSaida  AS HANDLE.
DEF VAR lcSaida AS LONGCHAR.

DEF INPUT PARAM vlcentrada as longchar. /* JSON ENTRADA */
DEF INPUT PARAM vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */

RUN LOG("INICIO").

/*
DEF WORK-TABLE wtimpostos NO-UNDO
    FIELD imposto  AS CHAR
    FIELD vBC       AS DEC
    FIELD valor AS DEC.
*/   
def temp-table ttentrada NO-UNDO   serialize-name "entradaDados"/* JSON ENTRADA */
    field anoImposto AS INT
    FIELD mesImposto  AS INT
    FIELD imposto  AS CHAR
    FIELD porcst  AS LOG.

DEF TEMP-TABLE ttimpostos NO-UNDO serialize-name "imposto"
    field ano AS INT
    FIELD mes  AS INT
    FIELD IDX_imposto    AS CHAR
    FIELD imposto    AS CHAR
    FIELD IDX-CST        like fisnotaproduicms.CST SERIALIZE-HIDDEN
    FIELD CST        like fisnotaproduicms.CST
    FIELD nomeCST    LIKE fiscst.nomeCST
    FIELD vBC        AS DEC
    FIELD valor      AS DEC
    INDEX X IS UNIQUE PRIMARY ano ASC mes ASC IDX_imposto ASC idx-cst ASC imposto ASC cst asc.
    
DEF TEMP-TABLE ttnotas NO-UNDO serialize-name "notas"
    field ano           AS INT  SERIALIZE-HIDDEN
    FIELD mes           AS INT  SERIALIZE-HIDDEN
    FIELD IDX_imposto   AS CHAR SERIALIZE-HIDDEN
    FIELD imposto       AS CHAR SERIALIZE-HIDDEN
    FIELD IDX-CST       AS CHAR SERIALIZE-HIDDEN
    FIELD CST           AS CHAR SERIALIZE-HIDDEN
    FIELD idnota        AS INT
    FIELD nitem        AS INT 
    INDEX X IS UNIQUE PRIMARY ano ASC mes ASC IDX_imposto ASC idx-cst ASC imposto ASC cst asc
    idnota ASC nitem ASC.

    
DEF DATASET dsExemplo  SERIALIZE-HIDDEN
    FOR ttimpostos,ttnotas
   DATA-RELATION tab1-tab2 FOR ttimpostos, ttnotas NESTED  
        RELATION-FIELDS (ano, ano, 
                         mes, mes,
                         IDX_imposto, IDX_imposto,
                         IDX-CST, IDX-CST,
                         imposto, imposto,
                         CST, CST).
 
 
def temp-table ttfisnotaproduicms  no-undo serialize-name "fisnotaproduicms"  /* JSON SAIDA */
    like fisnotaproduicms
    FIELD nomeCST LIKE fiscst.nomeCST
    INDEX X imposto ASC.
 
def temp-table ttfisnotaproduimposto  no-undo serialize-name "fisnotaproduimposto"  /* JSON SAIDA */
    like fisnotaproduimposto
    INDEX X imposto ASC.

DEF VAR vdtini  AS DATE.
DEF VAR vdtfim  AS DATE.
DEF VAR vidx_imposto    AS CHAR.
DEF VAR vidx-cst    AS CHAR.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada NO-ERROR.
if NOT AVAIL ttentrada then do:
    RETURN.
end.


RUN LOG("ANO: " + string(ttentrada.anoImposto)).
RUN LOG("MES: " + string(ttentrada.mesImposto)).

/* primeiro dia de um mes */
vdtini = DATE(ttentrada.mesImposto,01,ttentrada.anoImposto).
/* ultimo dia de um mes */
vdtfim = DATE(IF ttentrada.mesImposto + 1 = 13 THEN 1 ELSE ttentrada.mesImposto + 1,01,
              IF ttentrada.mesImposto + 1 = 13 THEN ttentrada.anoImposto + 1 ELSE ttentrada.anoImposto).

FOR EACH fisnota WHERE 
        fisnota.dtEmissao >= vdtini AND 
        fisnota.dtEmissao <= vdtfim
                    NO-LOCK:
    FOR EACH fisnotaproduto OF fisnota NO-LOCK:
        RUN impostos/database/fisnotaprodu-calcula.p (  INPUT ROWID(fisnotaproduto),
                                                        INPUT-OUTPUT table ttfisnotaproduicms,
                                                        INPUT-OUTPUT table ttfisnotaproduimposto).
    END.
    
END.


for EACH ttfisnotaproduicms
    BREAK 
    by ttfisnotaproduicms.idnota
    by ttfisnotaproduicms.nitem
    BY ttfisnotaproduicms.imposto:
    IF  ttfisnotaproduicms.imposto = "PIS" OR ttfisnotaproduicms.imposto = "COFINS" OR ttfisnotaproduicms.imposto = "ICMS"
    THEN DO:
        vidx_imposto =  ttfisnotaproduicms.imposto.
        vidx-cst     =  ttfisnotaproduicms.cst.
    END.
    
    FIND fisnota OF ttfisnotaproduicms NO-LOCK.
    FIND ttimpostos WHERE ttimpostos.ano       =  year(fisnota.dtEmissao) AND
                          ttimpostos.mes       =  MONTH(fisnota.dtEmissao) AND
                          ttimpostos.idx_imposto =  vidx_imposto AND
                          ttimpostos.imposto   =  ttfisnotaproduicms.imposto AND
                          ttimpostos.IDX-CST   =  (IF ttentrada.porcst = FALSE THEN "" ELSE vidx-cst) AND
                          ttimpostos.CST       =  (IF ttentrada.porcst = FALSE THEN "" ELSE ttfisnotaproduicms.CST)
                          
                          NO-ERROR.
                          
    if NOT AVAIL ttimpostos then do:
        CREATE ttimpostos.
        ttimpostos.ano       =  year(fisnota.dtEmissao).
        ttimpostos.mes       =  MONTH(fisnota.dtEmissao).
        ttimpostos.idx_imposto =  vidx_imposto.
        ttimpostos.imposto   =  ttfisnotaproduicms.imposto.
        ttimpostos.IDX-CST   =  (IF ttentrada.porcst = FALSE THEN "" ELSE vidx-cst).
        ttimpostos.CST       =  (IF ttentrada.porcst = FALSE THEN "" ELSE ttfisnotaproduicms.CST).
        FIND fiscst WHERE fiscst.cst = ttimpostos.CST NO-LOCK NO-ERROR.  
        IF AVAIL fiscst
        THEN DO:
            ttimpostos.nomeCST   = fiscst.nomeCST.
        END. 
    end.
    ttimpostos.vBC      = ttimpostos.vBC + ttfisnotaproduicms.vBC.
    ttimpostos.valor    = ttimpostos.valor + ttfisnotaproduicms.vICMS.
    
    FIND ttnotas WHERE ttnotas.ano = ttimpostos.ano AND
                       ttnotas.mes = ttimpostos.mes AND
                       ttnotas.IDX_imposto = ttimpostos.IDX_imposto AND
                       ttnotas.imposto = ttimpostos.imposto AND
                       ttnotas.IDX-CST = ttimpostos.IDX-CST AND
                       ttnotas.CST = ttimpostos.CST AND
                       ttnotas.idnota = ttfisnotaproduicms.idNota AND
                       ttnotas.nitem = ttfisnotaproduicms.nitem 
                       NO-LOCK NO-ERROR.
    IF NOT AVAIL ttnotas
    THEN DO:
        IF ttimpostos.imposto = "COFINS_CALC" OR ttimpostos.imposto = "ICMS_CALC" OR ttimpostos.imposto = "PIS_CALC" THEN
        DO:
            CREATE ttnotas.
            ttnotas.ano           = ttimpostos.ano.
            ttnotas.mes           = ttimpostos.mes.
            ttnotas.IDX_imposto   = ttimpostos.IDX_imposto.
            ttnotas.imposto       = ttimpostos.imposto.
            ttnotas.IDX-CST       = ttimpostos.IDX-CST.
            ttnotas.CST           = ttimpostos.CST.
            ttnotas.idnota        = ttfisnotaproduicms.idNota.
            ttnotas.nitem         = ttfisnotaproduicms.nitem.
        END.
        
    END.
    
END.

for EACH ttfisnotaproduimposto
    BREAK 
    by ttfisnotaproduimposto.idnota
    by ttfisnotaproduimposto.nitem
    BY ttfisnotaproduimposto.imposto:
    IF  ttfisnotaproduimposto.imposto = "PIS" OR ttfisnotaproduimposto.imposto = "COFINS" OR ttfisnotaproduimposto.imposto = "ICMS"
    THEN DO:
        vidx_imposto =  ttfisnotaproduimposto.imposto.
        vidx-cst     =  ttfisnotaproduimposto.cst.
    END.
   

    FIND fisnota OF ttfisnotaproduimposto NO-LOCK.
    FIND ttimpostos WHERE ttimpostos.ano       =  year(fisnota.dtEmissao) AND
                          ttimpostos.mes       =  MONTH(fisnota.dtEmissao) AND
                          ttimpostos.idx_imposto =  vidx_imposto AND
                          ttimpostos.imposto   =  ttfisnotaproduimposto.imposto AND
                          ttimpostos.IDX-CST   =  (IF ttentrada.porcst = FALSE THEN "" ELSE vidx-cst) AND
                          ttimpostos.CST       =  (IF ttentrada.porcst = FALSE THEN "" ELSE ttfisnotaproduimposto.CST)
                          NO-ERROR.
                        
    if NOT AVAIL ttimpostos then do:
        CREATE ttimpostos.
        ttimpostos.ano       =  year(fisnota.dtEmissao).
        ttimpostos.mes       =  MONTH(fisnota.dtEmissao).
        ttimpostos.idx_imposto =  vidx_imposto.
        ttimpostos.imposto   =  ttfisnotaproduimposto.imposto.
        ttimpostos.IDX-CST   =  (IF ttentrada.porcst = FALSE THEN "" ELSE vidx-cst).
        ttimpostos.CST       =  (IF ttentrada.porcst = FALSE THEN "" ELSE ttfisnotaproduimposto.CST).
        FIND fiscst WHERE fiscst.cst = ttimpostos.CST NO-LOCK NO-ERROR.  
        IF AVAIL fiscst
        THEN DO:
            ttimpostos.nomeCST   = fiscst.nomeCST.
        END. 
    end.
    ttimpostos.vBC     = ttimpostos.vBC + ttfisnotaproduimposto.vBC.
    ttimpostos.valor   = ttimpostos.valor + ttfisnotaproduimposto.valor.
    
    FIND ttnotas WHERE ttnotas.ano = ttimpostos.ano AND
                       ttnotas.mes = ttimpostos.mes AND
                       ttnotas.IDX_imposto = ttimpostos.IDX_imposto AND
                       ttnotas.imposto = ttimpostos.imposto AND
                       ttnotas.IDX-CST = ttimpostos.IDX-CST AND
                       ttnotas.CST = ttimpostos.CST AND
                       ttnotas.idnota = ttfisnotaproduicms.idNota AND
                       ttnotas.nitem = ttfisnotaproduicms.nitem 
                       NO-LOCK NO-ERROR.
    IF NOT AVAIL ttnotas
    THEN DO:        
        IF ttimpostos.imposto = "COFINS_CALC" OR ttimpostos.imposto = "ICMS_CALC" OR ttimpostos.imposto = "PIS_CALC" THEN
        DO:
            CREATE ttnotas.
            ttnotas.ano           = ttimpostos.ano.
            ttnotas.mes           = ttimpostos.mes.
            ttnotas.IDX_imposto   = ttimpostos.IDX_imposto.
            ttnotas.imposto       = ttimpostos.imposto.
            ttnotas.IDX-CST       = ttimpostos.IDX-CST.
            ttnotas.CST           = ttimpostos.CST.
            ttnotas.idnota        = ttfisnotaproduimposto.idNota.
            ttnotas.nitem         = ttfisnotaproduimposto.nitem.
        END.
        
    END.
        
END.

if ttentrada.imposto <> ? then do:
    for EACH ttimpostos:
        if ttimpostos.idx_imposto <> ttentrada.imposto 
        THEN DO:
            DELETE ttimpostos.
        END.
    end.
    
end.
hsaida =  DATASET dsExemplo:HANDLE. 
//hsaida =  TEMP-TABLE ttimpostos:HANDLE.   
/* EMPTY TEMP-TABLE ttimpostos.

for EACH wtimpostos:
    CREATE ttimpostos.
    BUFFER-COPY wtimpostos TO ttimpostos.
end.
*/
hsaida:WRITE-JSON("LONGCHAR", lcSaida, TRUE).

    
put unformatted STRING(lcsaida) .


procedure LOG.
    DEF INPUT PARAM vmensagem AS CHAR.    
    OUTPUT TO VALUE(vtmp + "/impostos-calculo_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.


