
// Programa especializado em CRAR a tabela fisnotaproduimposto
def temp-table ttentrada no-undo serialize-name "fisnotaproduimposto"   /* JSON ENTRADA */
    LIKE fisnotaproduimposto.

  
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
    find fisnotaproduimposto where 
            fisnotaproduimposto.idNota = ttentrada.idNota AND
            fisnotaproduimposto.nItem = ttentrada.nItem AND
            fisnotaproduimposto.imposto = ttentrada.imposto 
            no-lock no-error.
    if avail fisnotaproduimposto
    then do:
        vmensagem = "fisnotaproduimposto ja cadastrada".
        return.  
    end.


    do on error undo:
        create fisnotaproduimposto.
        BUFFER-COPY ttentrada TO fisnotaproduimposto .
    end.

END.