
// Programa especializado em CRAR a tabela fisprocesso
def temp-table ttentrada no-undo serialize-name "fisprocesso"   /* JSON ENTRADA */
    LIKE fisprocesso.

  
def input param vAcao as char.
DEF INPUT PARAM TABLE FOR ttentrada.
def output param vmensagem as char.

vmensagem = ?.

find first ttentrada no-error.
if not avail ttentrada then do:
    vmensagem = "Dados de Entrada nao encontrados".
    return.    
end.

if ttentrada.nomeProcesso = ""
then do:
    vmensagem = "Dados de Entrada Invalidos".
    return.    
end.

if vAcao = "PUT"
THEN DO:
    /*find fisprocesso where fisprocesso.nomeProcesso = ttentrada.nomeProcesso 
            no-lock no-error.
    if avail fisprocesso
    then do:
        vmensagem = "Processo ja cadastrada".
        return.  
    end. */


    do on error undo:
        create fisprocesso.
        BUFFER-COPY ttentrada EXCEPT idProcesso TO fisprocesso .
    end.

END.