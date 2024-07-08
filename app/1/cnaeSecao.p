def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

{impostos/database/acentos.i}


def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idcnSecao like cnaeSecao.idcnSecao.

def temp-table ttcnaeSecao  no-undo serialize-name "cnaeSecao"  /* JSON SAIDA */
    field idcnSecao             like cnaeSecao.idcnSecao
    field descricao             like cnaeSecao.descricao
    field caracTrib             like cnaeSecao.caracTrib.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidcnSecao like ttentrada.idcnSecao.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


vidcnSecao = ?.
if avail ttentrada
then do:
    vidcnSecao = ttentrada.idcnSecao.
end.

IF ttentrada.idcnSecao <> ? OR ttentrada.idcnSecao = ?
THEN DO:
    for each cnaeSecao where
        (if vidcnSecao = ?
         then true /* TODOS */
         else cnaeSecao.idcnSecao = vidcnSecao)
         no-lock.

         create ttcnaeSecao.
         ttcnaeSecao.idcnSecao   = cnaeSecao.idcnSecao.
         ttcnaeSecao.descricao  = removeacento(cnaeSecao.descricao).
         ttcnaeSecao.caracTrib  = cnaeSecao.caracTrib.
    end.
END.


find first ttcnaeSecao no-error.

if not avail ttcnaeSecao
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "cnaeSecao nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttcnaeSecao:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


