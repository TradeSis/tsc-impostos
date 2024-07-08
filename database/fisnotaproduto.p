// Programa especializado em CRAR a tabela fisnotaproduto
def temp-table ttentrada no-undo serialize-name "fisnotaproduto"   /* JSON ENTRADA */
    LIKE fisnotaproduto.

  
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
    find fisnotaproduto where 
            fisnotaproduto.idNota = ttentrada.idNota AND
            fisnotaproduto.nItem = ttentrada.nItem 
            no-lock no-error.
    if avail fisnotaproduto
    then do:
        vmensagem = "fisnotaproduto ja cadastrada".
        return.  
    end.

    do on error undo:
        create fisnotaproduto.
        BUFFER-COPY ttentrada TO fisnotaproduto .
    end.

END.    

