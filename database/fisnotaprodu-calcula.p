def temp-table ttfisnotaproduicms  no-undo serialize-name "fisnotaproduicms"  /* JSON SAIDA */
    like fisnotaproduicms
    FIELD nomeCST LIKE fiscst.nomeCST
    INDEX X imposto ASC.

def temp-table ttfisnotaproduimposto  no-undo serialize-name "fisnotaproduimposto"  /* JSON SAIDA */
    like fisnotaproduimposto
    INDEX X imposto ASC.

DEF INPUT PARAM vrowid AS ROWID.
DEF INPUT-OUTPUT PARAM TABLE FOR ttfisnotaproduicms. 
DEF INPUT-OUTPUT PARAM TABLE FOR ttfisnotaproduimposto. 

FIND fisnotaproduto WHERE ROWID(fisnotaproduto) = vrowid NO-LOCK.

//Produto ICMS
FOR EACH fisnotaproduicms OF fisnotaproduto NO-LOCK.
    create ttfisnotaproduicms.
    BUFFER-COPY fisnotaproduicms TO ttfisnotaproduicms.
    FIND fiscst OF fisnotaproduicms NO-LOCK NO-ERROR.
    if AVAIL fiscst then do:
        ttfisnotaproduicms.nomeCST = fiscst.nomeCST.
    end.
    
END.

RUN impostos/database/fisnotaproduicms-cal.p (  INPUT ROWID(fisnotaproduto),
                                                INPUT-OUTPUT table ttfisnotaproduicms).                                

//Produto IMPOSTO
for each fisnotaproduimposto OF fisnotaproduto NO-LOCK.
    create ttfisnotaproduimposto.
    BUFFER-COPY fisnotaproduimposto TO ttfisnotaproduimposto.

end.

RUN impostos/database/fisnotaproduimposto-cal.p (  INPUT ROWID(fisnotaproduto),
                                                   INPUT-OUTPUT table ttfisnotaproduimposto). 
