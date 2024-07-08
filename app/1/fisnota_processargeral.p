DEF INPUT PARAM vlcentrada AS LONGCHAR.
DEF INPUT PARAM vtmp        AS CHAR.
 
def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */
def var vlcsaida   as longchar.         /* JSON SAIDA */

RUN LOG(" INICIO ").
def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idEmpresa  AS INT.

    
def temp-table ttprocessar no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNota  LIKE fisnota.idNota
    field idEmpresa  AS INT.
    
    
def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.
    
DEF VAR hprocessar AS HANDLE.
DEF VAR vlcProcessar AS LONGCHAR.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada.

run log("empresa=" + string(ttentrada.idempresa)).




for EACH fisnota WHERE idStatusNota = 0 NO-LOCK:
    
        CREATE ttprocessar.
        ttprocessar.idEmpresa   = ttentrada.idempresa.
        ttprocessar.idNota =  fisnota.idnota.

        RUN LOG("Nota=" + STRING(fisnota.idnota)).
        hprocessar  = temp-table ttprocessar:handle.
        
        lokJson = hprocessar:WRITE-JSON("LONGCHAR", vlcProcessar, TRUE).   
        
        RUN impostos/app/1/fisnota_processar.p ( INPUT vlcProcessar,
                                                 INPUT vtmp).
        FOR EACH ttprocessar:
            DELETE  ttprocessar.    
        END.    

end.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


procedure LOG.
    DEF INPUT PARAM vmensagem AS CHAR.    
    OUTPUT TO VALUE(vtmp + "/fisnota_processargeral_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.

