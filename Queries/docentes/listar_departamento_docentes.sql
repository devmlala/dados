
SELECT
    L.codpes,
    L.nompes,
    L.codset,
    L.nomabvset,
    L.nomset
FROM fflch.dbo.LOCALIZAPESSOA L
       WHERE (L.tipvinext = 'Docente' OR L.tipvinext = 'Docente Aposentado')
       AND L.codundclg = 8
       AND L.sitatl LIKE 'A'
