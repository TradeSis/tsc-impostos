def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "apifiscalhistorico"   /* JSON ENTRADA */
    field idHistorico  like apifiscalhistorico.idHistorico.

def temp-table ttapifiscalhistorico  no-undo serialize-name "apifiscalhistorico"  /* JSON SAIDA */
    LIKE apifiscalhistorico.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidHistorico like ttentrada.idHistorico.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidHistorico = 0.
if avail ttentrada
then do:
    vidHistorico = ttentrada.idHistorico.  
    if vidHistorico = ? then vidHistorico = 0. 
end.
 
IF ttentrada.idHistorico <> ? 
THEN DO:
      for EACH apifiscalhistorico WHERE
        apifiscalhistorico.idHistorico = vidHistorico 
        no-lock.
        
        create ttapifiscalhistorico.
        BUFFER-COPY apifiscalhistorico TO ttapifiscalhistorico.

    end. 
END.

IF ttentrada.idHistorico = ? 
THEN DO:
      for EACH apifiscalhistorico 
        no-lock.
        
        create ttapifiscalhistorico.
        BUFFER-COPY apifiscalhistorico TO ttapifiscalhistorico. 

    end. 
END. 


find first ttapifiscalhistorico no-error.

if not avail ttapifiscalhistorico
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "apifiscalhistorico nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttapifiscalhistorico:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


