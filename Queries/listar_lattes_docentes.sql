SELECT * FROM localizapessoa L
    WHERE (L.tipvinext = 'Docente' OR L.tipvinext = 'Docente Aposentado')
      AND L.codundclg IN (" . getenv('REPLICADO_CODUNDCLG') . ")
    ORDER BY L.nompes
    LIMIT 100

-- funcional: 
SELECT TOP 100* FROM LOCALIZAPESSOA L
    WHERE (L.tipvinext = 'Docente' OR L.tipvinext = 'Docente Aposentado')
    ORDER BY L.nompes;