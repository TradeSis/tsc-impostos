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

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.   

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


IF ttentrada.idNota <> ? AND ttentrada.nItem <> ?
THEN DO:

    FOR EACH fisnotaproduto where 
        fisnotaproduto.idNota = ttentrada.idNota AND
        fisnotaproduto.nItem = ttentrada.nItem 
        NO-LOCK.
        
        FOR EACH fisnotaproduicms OF fisnotaproduto NO-LOCK.

            create ttfisnotaproduicms.
            BUFFER-COPY fisnotaproduicms TO ttfisnotaproduicms.
            FIND fiscst OF fisnotaproduicms NO-LOCK NO-ERROR.
            if AVAIL fiscst then do:
                ttfisnotaproduicms.nomeCST = fiscst.nomeCST.
            end.
            
         END.
     

        RUN impostos/database/fisnotaproduicms-cal.p (  INPUT ROWID(fisnotaproduto),
                                                        INPUT-OUTPUT table ttfisnotaproduicms).                                

    END.
            
END.

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

hsaida  = TEMP-TABLE ttfisnotaproduicms:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

procedure LOG.
    DEF INPUT PARAM vmensagem AS CHAR.    
    OUTPUT TO VALUE(vtmp + "/fisnotaproduicms_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.
    


