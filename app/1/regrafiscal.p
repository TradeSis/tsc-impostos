def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscalregra"   /* JSON ENTRADA */
    field idRegra like fiscalregra.idRegra
    field codRegra like fiscalregra.codRegra
    field codExcecao like fiscalregra.codExcecao.

def temp-table ttfiscalregra  no-undo serialize-name "fiscalregra"  /* JSON SAIDA */
    LIKE fiscalregra.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidRegra like ttentrada.idRegra.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidRegra = 0.
if avail ttentrada
then do:
    vidRegra = ttentrada.idRegra.
    if vidRegra = ? then vidRegra = 0.
end.

IF ttentrada.idRegra <> ? OR (ttentrada.idRegra = ? AND ttentrada.codRegra = ?)
THEN DO:
    for each fiscalregra where
        (if vidRegra = 0
         then true /* TODOS */
         else fiscalregra.idRegra = vidRegra)
         no-lock.

         RUN criaRegras.

    end.
END.

IF ttentrada.codRegra <> ? AND ttentrada.codExcecao <> ?
THEN DO:
      for each fiscalregra WHERE 
        fiscalregra.codRegra = ttentrada.codRegra AND fiscalregra.codExcecao = ttentrada.codExcecao
        no-lock.
        
        RUN criaRegras.

    end. 
END.

IF ttentrada.codRegra <> ? AND ttentrada.codExcecao = ? 
THEN DO:
      for each fiscalregra WHERE 
        fiscalregra.codRegra = ttentrada.codRegra
        no-lock.
        
        RUN criaRegras.

    end. 
END.

find first ttfiscalregra no-error.

if not avail ttfiscalregra
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Regra nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfiscalregra:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


PROCEDURE criaRegras.

    create ttfiscalregra.
    BUFFER-COPY fiscalregra TO ttfiscalregra.

END.
