
// Programa especializado em CRAR a tabela fisnotatotal
def temp-table ttentrada no-undo serialize-name "fisnotatotal"   /* JSON ENTRADA */
    LIKE fisnotatotal.

  
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
    find fisnotatotal where fisnotatotal.idNota = ttentrada.idNota no-lock no-error.
    if avail fisnotatotal
    then do:
        vmensagem = "fisnotatotal ja cadastrada".
        return.  
    end.


    do on error undo:
        create fisnotatotal.
        BUFFER-COPY ttentrada TO fisnotatotal .
    end.

END.