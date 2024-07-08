def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idProcesso  like fisprocesso.idProcesso.

def temp-table ttfisprocesso  no-undo serialize-name "fisprocesso"  /* JSON SAIDA */
    like fisprocesso.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidProcesso like ttentrada.idProcesso.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidProcesso = 0.
if avail ttentrada
then do:
    vidProcesso = ttentrada.idProcesso.
    if vidProcesso = ? then vidProcesso = 0.
end.

IF ttentrada.idProcesso <> ? OR (ttentrada.idProcesso = ?)
THEN DO:
    for each fisprocesso where
    (if vidProcesso = 0
    then true /* TODOS */
    else fisprocesso.idProcesso = vidProcesso)
    no-lock.
    
    create ttfisprocesso.
    BUFFER-COPY fisprocesso TO ttfisprocesso.
    
    end.
END.


find first ttfisprocesso no-error.

if not avail ttfisprocesso
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Processo nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisprocesso:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

