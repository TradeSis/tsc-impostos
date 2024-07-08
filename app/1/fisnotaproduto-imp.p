def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */
RUN LOG("INICIO").
def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNota  like fisnotaproduicms.idNota
    field nItem  like fisnotaproduicms.nItem.

def temp-table ttfisnotaproduicms  no-undo serialize-name "fisnotaproduicms"  /* JSON SAIDA */
    like fisnotaproduicms
    FIELD nomeCST LIKE fiscst.nomeCST
    INDEX X imposto ASC.

def temp-table ttfisnotaproduimposto  no-undo serialize-name "fisnotaproduimposto"  /* JSON SAIDA */
    like fisnotaproduimposto
    INDEX X imposto ASC.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

DEF DATASET fisnotaprodutogeral 
    FOR ttfisnotaproduicms, ttfisnotaproduimposto.
            

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


IF ttentrada.idNota <> ? AND ttentrada.nItem <> ?
THEN DO:

    FOR EACH fisnotaproduto where 
        fisnotaproduto.idNota = ttentrada.idNota AND
        fisnotaproduto.nItem = ttentrada.nItem 
        NO-LOCK.

        RUN impostos/database/fisnotaprodu-calcula.p (  INPUT ROWID(fisnotaproduto),
                                                        INPUT-OUTPUT table ttfisnotaproduicms,
                                                        INPUT-OUTPUT table ttfisnotaproduimposto).                               
    
    END.
            
END.
/*
find first ttfisnotaproduicms no-error.

if not avail ttfisnotaproduicms
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Nota nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.
*/

hsaida  = DATASET fisnotaprodutogeral:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

procedure LOG.
    DEF INPUT PARAM vmensagem AS CHAR.    
    OUTPUT TO VALUE(vtmp + "/fisnotaproduto-imp" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.
    



