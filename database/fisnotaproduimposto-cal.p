def temp-table ttfisnotaproduimposto  no-undo serialize-name "fisnotaproduimposto" 
    like fisnotaproduimposto
    INDEX X imposto ASC.

DEF BUFFER dttfisnotaproduimposto FOR ttfisnotaproduimposto.
    
DEF VAR vpercentual AS CHAR.

FIND FIRST apifiscal NO-LOCK.

DEF INPUT PARAM vrowid AS ROWID.
DEF INPUT-OUTPUT PARAM TABLE FOR ttfisnotaproduimposto.

FIND fisnotaproduto WHERE ROWID(fisnotaproduto) = vrowid NO-LOCK.
FIND fisnota OF fisnotaproduto NO-LOCK.
FIND pessoas WHERE pessoas.idpessoa = fisnota.idpessoaemitente NO-LOCK.
FIND geralpessoas OF pessoas NO-LOCK.


FOR EACH fisnotaproduimposto OF fisnotaproduto NO-LOCK:

    create ttfisnotaproduimposto.
    ttfisnotaproduimposto.idNota = fisnotaproduimposto.idNota. 
    ttfisnotaproduimposto.nItem    = fisnotaproduimposto.nItem.
    ttfisnotaproduimposto.imposto = fisnotaproduimposto.imposto + "_CALC".
    ttfisnotaproduimposto.nomeImposto = fisnotaproduimposto.nomeImposto.
    ttfisnotaproduimposto.CST = fisnotaproduimposto.CST.
    ttfisnotaproduimposto.vBC = 0. 
    ttfisnotaproduimposto.valor = 0.
     
    FIND produto OF   fisnotaproduto NO-LOCK.
    FIND geralproduto where geralproduto.idgeralproduto =  produto.idgeralproduto NO-LOCK  NO-ERROR.
    if AVAIL geralprodutos 
    then do:
        FIND geralfornecimento WHERE geralfornecimento.cnpj = geralpessoas.cpfcnpj AND
                                         geralfornecimento.idgeralproduto =  geralproduto.idgeralproduto
                                         NO-LOCK NO-ERROR.
        
        FIND fiscalgrupo OF geralproduto NO-LOCK NO-ERROR.
        if AVAIL fiscalgrupo AND AVAIL geralfornecimento
        then do:
        
            FIND fiscaloperacao WHERE 
                            fiscaloperacao.idGrupo = fiscalgrupo.idGrupo AND
                            fiscaloperacao.codigoEstado = geralpessoas.codigoEstado AND
                            fiscaloperacao.cFOP = geralfornecimento.cfop AND
                            fiscaloperacao.codigoCaracTrib = STRING (geralpessoas.caracTrib) AND 
                            fiscaloperacao.finalidade = string(apifiscal.finalidade)
            NO-LOCK NO-ERROR.
            if AVAIL fiscaloperacao  
            then do:
                FIND fiscalregra OF fiscaloperacao NO-LOCK.
                    
                ttfisnotaproduimposto.cEnq = fisnotaproduimposto.cEnq. 
                ttfisnotaproduimposto.CST = fiscalregra.CST. //fisnotaproduimposto.CST * 2.
                ttfisnotaproduimposto.vBC = fisnotaproduto.valorTotal. 
                            
                IF fisnotaproduimposto.imposto = "COFINS" 
                THEN DO:
                vpercentual = string(fiscalgrupo.aliqCofins). 
                END.
                IF fisnotaproduimposto.imposto = "PIS" 
                THEN DO:
                    vpercentual = string(fiscalgrupo.aliqPis).
                END.  
                ttfisnotaproduimposto.percentual = INT(vpercentual). //fisnotaproduimposto.percentual * 2
                ttfisnotaproduimposto.valor = ( fisnotaproduto.valorTotal *  INT(vpercentual))  / 100. //fisnotaproduimposto.valor * 2. 
                
            end.
           

        END.
        
    end.
    
    create dttfisnotaproduimposto.
    dttfisnotaproduimposto.idNota = fisnotaproduimposto.idNota. 
    dttfisnotaproduimposto.nItem    = fisnotaproduimposto.nItem.
    dttfisnotaproduimposto.imposto = fisnotaproduimposto.imposto + "_DIF".
    dttfisnotaproduimposto.vBC =  fisnotaproduimposto.vBC - ttfisnotaproduimposto.vBC. 
    dttfisnotaproduimposto.valor =  fisnotaproduimposto.valor - ttfisnotaproduimposto.valor. 
    
END.

