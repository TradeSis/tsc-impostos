def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */
RUN LOG("INICIO").
def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNota  like fisnotaproduimposto.idNota
    field nItem  like fisnotaproduimposto.nItem.

def temp-table ttfisnotaproduimposto  no-undo serialize-name "fisnotaproduimposto"  /* JSON SAIDA */
    like fisnotaproduimposto
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
        
        for each fisnotaproduimposto OF fisnotaproduto NO-LOCK.

            create ttfisnotaproduimposto.
            BUFFER-COPY fisnotaproduimposto TO ttfisnotaproduimposto.
        
        end.

        RUN impostos/database/fisnotaproduimposto-cal.p (  INPUT ROWID(fisnotaproduto),
                                                           INPUT-OUTPUT table ttfisnotaproduimposto).                                

    END.
END.
  

find first ttfisnotaproduimposto no-error.

if not avail ttfisnotaproduimposto
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Nota nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisnotaproduimposto:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

procedure LOG.
    DEF INPUT PARAM vmensagem AS CHAR.    
    OUTPUT TO VALUE(vtmp + "/fisnotaproduimposto_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.


    



