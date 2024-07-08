// Programa especializado em CRAR a tabela fisnotaproduicms
def temp-table ttentrada no-undo serialize-name "fisnotaproduicms"   /* JSON ENTRADA */
    LIKE fisnotaproduicms.

  
def input param vAcao as char.
DEF INPUT PARAM TABLE FOR ttentrada.
def output param vmensagem as char.

vmensagem = ?.

find first ttentrada no-error.
if not avail ttentrada then do:
    vmensagem = "Dados de Entrada nao encontrados".
    return.    
end.


if ttentrada.idNota = ?
then do:
    vmensagem = "Dados de Entrada Invalidos".
    return.    
end.


if vAcao = "PUT"
THEN DO:
    find fisnotaproduicms where 
            fisnotaproduicms.idNota = ttentrada.idNota AND
            fisnotaproduicms.nItem = ttentrada.nItem AND
            fisnotaproduicms.imposto = ttentrada.imposto 
            no-lock no-error.
    if avail fisnotaproduicms
    then do:
        vmensagem = "fisnotaproduicms ja cadastrada".
        return.  
    end.


    do on error undo:
        create fisnotaproduicms.
        BUFFER-COPY ttentrada TO fisnotaproduicms .
    end.

END.
