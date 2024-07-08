def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field chaveNFe  like fisnota.chaveNFe
    field idNota  like fisnota.idNota
    FIELD statusNota AS CHAR
    field anoImposto AS INT
    FIELD mesImposto  AS INT.

def temp-table ttfisnota  no-undo serialize-name "fisnota"  /* JSON SAIDA */
    FIELD idNota                     LIKE fisnota.idNota
    FIELD chaveNFe                   LIKE fisnota.chaveNFe
    FIELD naturezaOp                 LIKE fisnota.naturezaOp
    FIELD modelo                     LIKE fisnota.modelo
    FIELD serie                      LIKE fisnota.serie
    FIELD NF                         LIKE fisnota.NF
    FIELD dtEmissao                  LIKE fisnota.dtEmissao
    FIELD idPessoaEmitente           LIKE fisnota.idPessoaEmitente
    FIELD idPessoaDestinatario       LIKE fisnota.idPessoaDestinatario
    FIELD idStatusNota               LIKE fisnota.idStatusNota
    FIELD vNF                        LIKE fisnota.vNF
    FIELD vProd                      LIKE fisnota.vProd
    FIELD vFrete                     LIKE fisnota.vFrete
    FIELD vSeg                       LIKE fisnota.vSeg
    FIELD vDesc                      LIKE fisnota.vDesc
    FIELD vOutro                     LIKE fisnota.vOutro
    field emitente_cpfCnpj           like geralpessoas.cpfCnpj
    field emitente_IE                like geralpessoas.IE
    field emitente_nomePessoa        like geralpessoas.nomePessoa
    field emitente_nomeFantasia      like geralpessoas.nomeFantasia
    field emitente_municipio         like geralpessoas.municipio
    field emitente_codigoEstado      like geralpessoas.codigoEstado
    field emitente_pais              like geralpessoas.pais 
    field destinatario_cpfCnpj       like geralpessoas.cpfCnpj
    field destinatario_IE            like geralpessoas.IE
    field destinatario_nomePessoa    like geralpessoas.nomePessoa
    field destinatario_nomeFantasia  like geralpessoas.nomeFantasia
    field destinatario_municipio     like geralpessoas.municipio
    field destinatario_codigoEstado  like geralpessoas.codigoEstado
    field destinatario_pais          like geralpessoas.pais   
    field nomeStatusNota             like fisnotastatus.nomeStatusNota.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidNota like ttentrada.idNota.

DEF VAR vdtini  AS DATE.
DEF VAR vdtfim  AS DATE.
DEF VAR vidx_imposto    AS CHAR.
DEF VAR vidx-cst    AS CHAR.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidNota = 0.
if avail ttentrada
then do:
    vidNota = ttentrada.idNota.
    if vidNota = ? then vidNota = 0.
end.
if ttentrada.chaveNFe = ""
then do:
    ttentrada.chaveNFe = ?.
end.
if ttentrada.idNota = 0
then do:
    ttentrada.idNota = ?.
end.


IF ttentrada.idNota <> ? OR (ttentrada.idNota = ? AND ttentrada.chaveNFe = ? AND ttentrada.statusNota = ?)
THEN DO:
    for each fisnota where
        (if vidNota = 0
        then true /* TODOS */
        ELSE fisnota.idNota = vidNota)
        no-lock.

       if avail fisnota
       then do:
            RUN criaNotas.
       end.

    end.
END.

IF ttentrada.chaveNFe <> ?
THEN DO:
    find fisnota where 
        fisnota.chaveNFe =  ttentrada.chaveNFe 
        NO-LOCK no-error.
       
       if avail fisnota
       then do:
           RUN criaNotas.
       end.
END.

IF ttentrada.statusNota = "carga" AND (ttentrada.idNota = ?)
THEN DO:
    for each fisnota where 
        fisnota.idStatusNota = 0 
        NO-LOCK.
       
       if avail fisnota
       then do:
            RUN criaNotas.
       end.
    end.
END.

IF ttentrada.statusNota = "notas" AND (ttentrada.idNota = ?)
THEN DO:

/* primeiro dia de um mes */
vdtini = DATE(ttentrada.mesImposto,01,ttentrada.anoImposto).
/* ultimo dia de um mes */
vdtfim = DATE(IF ttentrada.mesImposto + 1 = 13 THEN 1 ELSE ttentrada.mesImposto + 1,01,
              IF ttentrada.mesImposto + 1 = 13 THEN ttentrada.anoImposto + 1 ELSE ttentrada.anoImposto).
              
    for each fisnota where 
        fisnota.idStatusNota = 1 AND
        fisnota.dtEmissao >= vdtini AND 
        fisnota.dtEmissao <= vdtfim
        NO-LOCK.
       
       if avail fisnota
       then do:
            RUN criaNotas.
       end.
    end.
END.


  

find first ttfisnota no-error.

if not avail ttfisnota
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Nota não encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisnota:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
//export vlcSaida.

PROCEDURE criaNotas.

    create ttfisnota.
    BUFFER-COPY fisnota TO ttfisnota.
    
    IF ttentrada.statusNota <> "carga"
    THEN DO:     
        FIND pessoas WHERE pessoas.idPessoa = fisnota.idPessoaEmitente NO-LOCK.
        FIND geralpessoas OF pessoas NO-LOCK no-error.
            IF AVAILABLE geralpessoas 
            THEN DO:
                ttfisnota.emitente_cpfCnpj = geralpessoas.cpfCnpj.
                ttfisnota.emitente_IE = geralpessoas.IE.
                ttfisnota.emitente_nomePessoa = geralpessoas.nomePessoa.
                ttfisnota.emitente_nomeFantasia = geralpessoas.nomeFantasia.
                ttfisnota.emitente_municipio = geralpessoas.municipio.
                ttfisnota.emitente_codigoEstado = geralpessoas.codigoEstado.
                ttfisnota.emitente_pais = geralpessoas.pais.
            END.
            
        FIND pessoas WHERE pessoas.idPessoa = fisnota.idPessoaDestinatario NO-LOCK.
        FIND geralpessoas OF pessoas NO-LOCK no-error.
        IF AVAILABLE geralpessoas 
        THEN DO:
            ttfisnota.destinatario_cpfCnpj = geralpessoas.cpfCnpj.
            ttfisnota.destinatario_IE = geralpessoas.IE.
            ttfisnota.destinatario_nomePessoa = geralpessoas.nomePessoa.
            ttfisnota.destinatario_nomeFantasia = geralpessoas.nomeFantasia.
            ttfisnota.destinatario_municipio = geralpessoas.municipio.
            ttfisnota.destinatario_codigoEstado = geralpessoas.codigoEstado.
            ttfisnota.destinatario_pais = geralpessoas.pais.
        END.
    END.
     
        FIND fisnotastatus OF fisnota NO-LOCK no-error.
        IF AVAILABLE fisnotastatus 
        THEN DO:
            ttfisnota.nomeStatusNota = fisnotastatus.nomeStatusNota.
        END.

END.
