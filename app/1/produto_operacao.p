def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */


def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idGeralProduto   like geralprodutos.idGeralProduto
    FIELD cpfCnpj          LIKE geralpessoas.cpfCnpj
    FIELD idEmpresa        AS INT.

def temp-table ttfiscalgrupo  no-undo serialize-name "fiscalgrupo"  
    LIKE fiscalgrupo
    field idGeralProduto   like geralprodutos.idGeralProduto.

def temp-table ttprodutooperacao  no-undo serialize-name "produtooperacao"  
    field idGeralProduto    like geralprodutos.idGeralProduto
    field idGrupo           like fiscalgrupo.idGrupo
    FIELD nomeGrupo         like fiscalgrupo.nomeGrupo
    field codigoEstado      AS CHAR
    field cFOP              like geralfornecimento.cfop
    field codigoCaracTrib   like geralpessoas.caracTrib
    field finalidade        like apifiscal.finalidade
    FIELD origem            LIKE geralfornecimento.origem
    field idRegra           LIKE fiscaloperacao.idRegra.

    
def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidGeralProduto like ttentrada.idGeralProduto.

DEF DATASET grupooperacao 
    FOR ttfiscalgrupo, ttprodutooperacao.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


IF ttentrada.cpfCnpj <> ?
THEN DO:
    FIND geralprodutos WHERE geralprodutos.idGeralProduto = ttentrada.idGeralProduto NO-LOCK no-error.
    IF NOT AVAIL geralprodutos
    THEN DO:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "geralprodutos fiscal nao encontrado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    END.
            
    FIND geralfornecimento WHERE geralfornecimento.cnpj = ttentrada.cpfCnpj AND
                                 geralfornecimento.idGeralProduto = ttentrada.idGeralProduto
                                 NO-LOCK no-error.
    IF NOT AVAIL geralfornecimento
    THEN DO:
         create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "geralfornecimento nao encontrada".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    END.
            
    FIND geralpessoas WHERE geralpessoas.cpfCnpj = ttentrada.cpfCnpj  NO-LOCK no-error.
    IF NOT AVAIL geralpessoas
    THEN DO:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "geralpessoas.cpfCnpj nao encontrado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    END.
            
    FIND apifiscal WHERE apifiscal.idEmpresa = ttentrada.idempresa AND apifiscal.fornecedor = "imendes" NO-LOCK NO-ERROR.
    IF NOT AVAIL apifiscal 
    THEN DO:
         create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "Apifiscal nao encontrada".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    END.

    FIND fiscalgrupo WHERE fiscalgrupo.idGrupo = geralprodutos.idGrupo NO-LOCK NO-ERROR.

    create ttfiscalgrupo.
    if AVAIL fiscalgrupo then do:
        BUFFER-COPY fiscalgrupo TO ttfiscalgrupo.

        IF fiscalgrupo.idGrupo <> ? AND 
        geralpessoas.codigoEstado <> ? AND 
        geralfornecimento.cfop <> ? AND 
        geralpessoas.caracTrib <> ? AND 
        apifiscal.finalidade <> ?
        THEN DO:
            for each fiscaloperacao WHERE 
                fiscaloperacao.idGrupo = fiscalgrupo.idGrupo AND
                fiscaloperacao.codigoEstado = geralpessoas.codigoEstado AND
                fiscaloperacao.cFOP = geralfornecimento.cfop AND
                fiscaloperacao.codigoCaracTrib = STRING (geralpessoas.caracTrib) AND 
                fiscaloperacao.finalidade = string(apifiscal.finalidade)
                no-lock.
                                        
                create ttprodutooperacao.        
                ttprodutooperacao.idGrupo = fiscalgrupo.idGrupo.
                ttprodutooperacao.nomeGrupo = fiscalgrupo.nomeGrupo.
                ttprodutooperacao.codigoEstado = fiscaloperacao.codigoEstado.
                ttprodutooperacao.cFOP = fiscaloperacao.cFOP.
                ttprodutooperacao.codigoCaracTrib = INT (fiscaloperacao.codigoCaracTrib).
                ttprodutooperacao.finalidade = INT (fiscaloperacao.finalidade).
                ttprodutooperacao.origem = INT (geralfornecimento.origem).
                ttprodutooperacao.idRegra = fiscaloperacao.idRegra.
            
            end. 
        END.
    end.
    ttfiscalgrupo.idGeralProduto = geralprodutos.idGeralProduto. 
           
END.

hsaida  = DATASET grupooperacao:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).







