def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNota  like fisnotaproduto.idNota
    FIELD nitem  like fisnotaproduto.nitem.

def temp-table ttfisnotaproduto  no-undo serialize-name "fisnotaproduto"  /* JSON SAIDA */
    like fisnotaproduto
    field refProduto       like produtos.refProduto
    field nomeProduto      LIKE produtos.nomeProduto
    field idGeralProduto   LIKE produtos.idGeralProduto
    field codigoNcm        like produtos.codigoNcm
    field codigoCest       like produtos.codigoCest
    field eanProduto       like produtos.eanProduto.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidNota like ttentrada.idNota.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidNota = 0.
if avail ttentrada
then do:
    vidNota = ttentrada.idNota.
    if vidNota = ? then vidNota = 0.
end.


IF ttentrada.idNota <> ? AND ttentrada.nitem = ? OR (ttentrada.idNota = ?)
THEN DO:
    for each fisnotaproduto where
        (if vidNota = 0
        then true /* TODOS */
        ELSE fisnotaproduto.idNota = vidNota)
        no-lock.

       if avail fisnotaproduto
       then do:
            create ttfisnotaproduto.
            BUFFER-COPY fisnotaproduto TO ttfisnotaproduto.
                
            FIND produtos WHERE produtos.idProduto = fisnotaproduto.idProduto NO-LOCK no-error.
            
            ttfisnotaproduto.refProduto = produtos.refProduto.
            ttfisnotaproduto.eanProduto = produtos.eanProduto.
            ttfisnotaproduto.nomeProduto = produtos.nomeProduto.
            ttfisnotaproduto.idGeralProduto = produtos.idGeralProduto.
            ttfisnotaproduto.codigoNcm = produtos.codigoNcm.
            ttfisnotaproduto.codigoCest = produtos.codigoCest.
                
       end.

    end.
END.
  
IF ttentrada.idNota <> ? AND ttentrada.nitem <> ?
THEN DO:
    for each fisnotaproduto  WHERE 
                        (fisnotaproduto.idnota = ttentrada.idNota AND
                        fisnotaproduto.nitem = ttentrada.nitem)
                        no-lock.

            create ttfisnotaproduto.
            ttfisnotaproduto.idnota = fisnotaproduto.idnota.
            ttfisnotaproduto.nitem   = fisnotaproduto.nitem.   
            FIND produtos WHERE produtos.idProduto = fisnotaproduto.idProduto NO-LOCK no-error.
            
            ttfisnotaproduto.refProduto = produtos.refProduto.
            ttfisnotaproduto.eanProduto = produtos.eanProduto.
            ttfisnotaproduto.nomeProduto = produtos.nomeProduto.
            ttfisnotaproduto.idGeralProduto = produtos.idGeralProduto.
            ttfisnotaproduto.codigoNcm = produtos.codigoNcm.
            ttfisnotaproduto.codigoCest = produtos.codigoCest.

    end.
END.


  

find first ttfisnotaproduto no-error.

if not avail ttfisnotaproduto
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Nota nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisnotaproduto:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


    



