// Programa especializado em CRAR a tabela fisnotaproduicms
def temp-table ttfisnotaproduicms no-undo serialize-name "fisnotaproduicms"   /* JSON ENTRADA */
    LIKE fisnotaproduicms
    FIELD nomeCST LIKE fiscst.nomeCST
    INDEX X imposto ASC.
    
DEF BUFFER dttfisnotaproduicms FOR ttfisnotaproduicms.

FIND FIRST apifiscal NO-LOCK.

DEF INPUT PARAM vrowid AS ROWID.
DEF INPUT-OUTPUT PARAM TABLE FOR ttfisnotaproduicms. 

FIND fisnotaproduto WHERE ROWID(fisnotaproduto) = vrowid NO-LOCK.
FIND fisnota OF fisnotaproduto NO-LOCK.
FIND pessoas WHERE pessoas.idpessoa = fisnota.idpessoaemitente NO-LOCK.
FIND geralpessoas OF pessoas NO-LOCK.
    
FOR EACH fisnotaproduicms OF fisnotaproduto NO-LOCK:

    CREATE ttfisnotaproduicms.
    ttfisnotaproduicms.idNota   = fisnotaproduicms.idNota. 
    ttfisnotaproduicms.nItem    = fisnotaproduicms.nItem.
           
    ttfisnotaproduicms.imposto = fisnotaproduicms.imposto + "_CALC".
    ttfisnotaproduicms.nomeImposto = fisnotaproduicms.nomeImposto.
    ttfisnotaproduicms.CST = fisnotaproduicms.CST.
     ttfisnotaproduicms.vBC = 0.
      ttfisnotaproduicms.vICMS = 0.
     
    FIND produto OF   fisnotaproduto NO-LOCK.
    FIND geralproduto WHERE geralproduto.idgeralproduto =  produto.idgeralproduto NO-LOCK NO-ERROR.
    
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
                FIND fiscalregra OF fiscaloperacao NO-LOCK NO-ERROR.
                if AVAIL fiscalregra 
                then do:
                    FIND fiscst OF fiscalregra NO-LOCK NO-ERROR.
                    
                    ttfisnotaproduicms.orig     = geralfornecimento.origem.    // geralfornecimento.origem.
                        
                    ttfisnotaproduicms.CSOSN = fiscalregra.CSOSN. 
                        
                    // ttfisnotaproduicms.modBCST = fisnotaproduicms.modBCST * 2.
                    ttfisnotaproduicms.pMVAST = fiscalregra.iVA. 
                    // ttfisnotaproduicms.vBCST = fisnotaproduicms.vBCST * 2. 
                    ttfisnotaproduicms.pICMSST = fiscalregra.aliqIcmsSt. 
                    // ttfisnotaproduicms.vICMSST = fisnotaproduicms.vICMSST * 2. 
                        
                    ttfisnotaproduicms.CST     = fiscalregra.CST.       // fiscalregra.CST.
                    ttfisnotaproduicms.nomeCST = IF AVAIL fiscst THEN fiscst.nomeCST ELSE "-".       // fiscst.nomeCST.
                    ttfisnotaproduicms.modBC = fisnotaproduicms.modBC. 
                       
                    ttfisnotaproduicms.vBC = fisnotaproduto.valorTotal.      // fisnotaproduto.valorTotal
                    ttfisnotaproduicms.pICMS = fiscalregra.aliqIcmsInterna.  // fiscalregra.aliqIcmsInterna
                    ttfisnotaproduicms.vICMS = ( fisnotaproduto.valorTotal *  fiscalregra.aliqIcmsInterna)  / 100.  // ( fisnotaproduto.valorTotal *  fiscalregra.aliqIcmsInterna)  / 100
                end.
            end.
        end.
    end.

    CREATE dttfisnotaproduicms.
    dttfisnotaproduicms.idNota   = fisnotaproduicms.idNota. 
    dttfisnotaproduicms.nItem    = fisnotaproduicms.nItem.
        
    dttfisnotaproduicms.imposto = fisnotaproduicms.imposto + "_DIF".
    dttfisnotaproduicms.vBC   = fisnotaproduicms.vBC - ttfisnotaproduicms.vBC.
    dttfisnotaproduicms.vICMS   = fisnotaproduicms.vICMS - ttfisnotaproduicms.vICMS.
        
END.


  

            

