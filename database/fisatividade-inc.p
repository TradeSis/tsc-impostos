
// Programa especializado em CRAR a tabela fisatividade
def temp-table ttentrada no-undo serialize-name "fisatividade"   /* JSON ENTRADA */
    LIKE fisatividade.

  
def input param vAcao as char.
DEF INPUT PARAM TABLE FOR ttentrada.
def output param vmensagem as char.

vmensagem = ?.

find first ttentrada no-error.
if not avail ttentrada then do:
    vmensagem = "Dados de Entrada nao encontrados".
    return.    
end.

if ttentrada.nomeAtividade = ""
then do:
    vmensagem = "Dados de Entrada Invalidos".
    return.    
end.

if vAcao = "PUT"
THEN DO:
    /*find fisatividade where fisatividade.nomeAtividade = ttentrada.nomeAtividade 
            no-lock no-error.
    if avail fisatividade
    then do:
        vmensagem = "Atividade ja cadastrada".
        return.  
    end. */


    do on error undo:
        create fisatividade.
        BUFFER-COPY ttentrada EXCEPT idAtividade TO fisatividade .
    end.

END.