def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNota  like fisnotaproduto.idNota
    FIELD nitem  like fisnotaproduto.nitem.

def temp-table ttfisnotaprodutos  no-undo serialize-name "fisnotaprodutos"  /* JSON SAIDA */
    like fisnotaproduto
    field refProduto       like produtos.refProduto
    field nomeProduto      like geralprodutos.nomeProduto
    field idGeralProduto   like geralprodutos.idGeralProduto
    FIELD Cnpj             like geralfornecimento.Cnpj
    FIELD NF               like fisnota.NF
    FIELD eanProduto       LIKE produtos.eanProduto.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidNota like ttentrada.idNota.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.

FOR EACH ttentrada:

    vidNota = 0.
    if avail ttentrada
    then do:
        vidNota = ttentrada.idNota.
        if vidNota = ? then vidNota = 0.
    end.
      
    IF ttentrada.idNota <> ? AND ttentrada.nitem <> ?
    THEN DO:
        FIND fisnota where fisnota.idnota = ttentrada.idNota NO-LOCK.
        find pessoas where pessoas.idpessoa = fisnota.idpessoaemitente no-lock.

        for each fisnotaproduto of fisnota 
                        where fisnotaproduto.nitem = ttentrada.nitem no-lock.
                
                
                create ttfisnotaprodutos.
                ttfisnotaprodutos.idnota = fisnotaproduto.idnota.
                ttfisnotaprodutos.nitem   = fisnotaproduto.nitem.   
                FIND produtos WHERE produtos.idProduto = fisnotaproduto.idProduto NO-LOCK no-error.
               
                    IF AVAILABLE produtos 
                    THEN DO:
                        ttfisnotaprodutos.refProduto = produtos.refProduto.
                        ttfisnotaprodutos.eanProduto = produtos.eanProduto.
                        ttfisnotaprodutos.nomeProduto = produtos.nomeProduto.
                        ttfisnotaprodutos.idGeralProduto = produtos.idGeralProduto.
                        ttfisnotaprodutos.Cnpj = pessoas.cpfCnpj.
                        ttfisnotaprodutos.NF = fisnota.NF.
                    END.

        end.
    END.
    

END.  

find first ttfisnotaprodutos no-error.
//FOR EACH ttfisnotaprodutos:


    if not avail ttfisnotaprodutos
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "Nota nao encontrada".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.

//END.

hsaida  = TEMP-TABLE ttfisnotaprodutos:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


    



