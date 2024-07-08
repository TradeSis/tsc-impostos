
// Programa especializado em CRAR a tabela fisoperacao
def temp-table ttentrada no-undo serialize-name "fisoperacao"   /* JSON ENTRADA */
    LIKE fisoperacao.

  
def input param vAcao as char.
DEF INPUT PARAM TABLE FOR ttentrada.
def output param vmensagem as char.

vmensagem = ?.

find first ttentrada no-error.
if not avail ttentrada then do:
    vmensagem = "Dados de Entrada nao encontrados".
    return.    
end.

if ttentrada.nomeOperacao = ""
then do:
    vmensagem = "Dados de Entrada Invalidos".
    return.    
end.

if vAcao = "PUT"
THEN DO:
    /*find fisoperacao where fisoperacao.nomeOperacao = ttentrada.nomeOperacao 
            no-lock no-error.
    if avail fisoperacao
    then do:
        vmensagem = "Processo ja cadastrada".
        return.  
    end. */


    do on error undo:
        create fisoperacao.
        BUFFER-COPY ttentrada EXCEPT idOperacao TO fisoperacao .
    end.

END.