def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idAtividade  like fisatividade.idAtividade.

def temp-table ttfisatividade  no-undo serialize-name "fisatividade"  /* JSON SAIDA */
    like fisatividade.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidAtividade like ttentrada.idAtividade.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidAtividade = 0.
if avail ttentrada
then do:
    vidAtividade = ttentrada.idAtividade.
    if vidAtividade = ? then vidAtividade = 0.
end.

IF ttentrada.idAtividade <> ? OR (ttentrada.idAtividade = ?)
THEN DO:
    for each fisatividade where
    (if vidAtividade = 0
    then true /* TODOS */
    else fisatividade.idAtividade = vidAtividade)
    no-lock.
    
    create ttfisatividade.
    BUFFER-COPY fisatividade TO ttfisatividade.
    
    end.
END.


find first ttfisatividade no-error.

if not avail ttfisatividade
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Atividade nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisatividade:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

