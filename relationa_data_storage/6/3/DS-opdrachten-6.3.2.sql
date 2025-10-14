-- function solution
DELIMITER $$
CREATE FUNCTION decode_html_entities(input_text VARCHAR(75))
RETURNS VARCHAR(75)
DETERMINISTIC
BEGIN
    DECLARE output_text VARCHAR(75);
    
    SET output_text = input_text;
    
    -- Basic entities (must be first!)
    SET output_text = REPLACE(output_text, '&amp;', '&');
    SET output_text = REPLACE(output_text, '&quot;', '"');
    SET output_text = REPLACE(output_text, '&apos;', "'");
    SET output_text = REPLACE(output_text, '&#39;', "'");
    SET output_text = REPLACE(output_text, '&lt;', '<');
    SET output_text = REPLACE(output_text, '&gt;', '>');
    SET output_text = REPLACE(output_text, '&nbsp;', ' ');
    
    -- Special characters
    SET output_text = REPLACE(output_text, '&copy;', '©');
    SET output_text = REPLACE(output_text, '&reg;', '®');
    SET output_text = REPLACE(output_text, '&trade;', '™');
    SET output_text = REPLACE(output_text, '&euro;', '€');
    SET output_text = REPLACE(output_text, '&pound;', '£');
    SET output_text = REPLACE(output_text, '&yen;', '¥');
    SET output_text = REPLACE(output_text, '&cent;', '¢');
    
    -- Math symbols
    SET output_text = REPLACE(output_text, '&plusmn;', '±');
    SET output_text = REPLACE(output_text, '&times;', '×');
    SET output_text = REPLACE(output_text, '&divide;', '÷');
    SET output_text = REPLACE(output_text, '&frac14;', '¼');
    SET output_text = REPLACE(output_text, '&frac12;', '½');
    SET output_text = REPLACE(output_text, '&frac34;', '¾');
    SET output_text = REPLACE(output_text, '&deg;', '°');
    
    -- Punctuation
    SET output_text = REPLACE(output_text, '&ndash;', '–');
    SET output_text = REPLACE(output_text, '&mdash;', '—');
    SET output_text = REPLACE(output_text, '&lsquo;', "'");
    SET output_text = REPLACE(output_text, '&rsquo;', "'");
    SET output_text = REPLACE(output_text, '&ldquo;', '"');
    SET output_text = REPLACE(output_text, '&rdquo;', '"');
    SET output_text = REPLACE(output_text, '&hellip;', '…');
    SET output_text = REPLACE(output_text, '&bull;', '•');
    SET output_text = REPLACE(output_text, '&middot;', '·');
    
    -- Accented A
    SET output_text = REPLACE(output_text, '&aacute;', 'á');
    SET output_text = REPLACE(output_text, '&agrave;', 'à');
    SET output_text = REPLACE(output_text, '&acirc;', 'â');
    SET output_text = REPLACE(output_text, '&atilde;', 'ã');
    SET output_text = REPLACE(output_text, '&auml;', 'ä');
    SET output_text = REPLACE(output_text, '&aring;', 'å');
    SET output_text = REPLACE(output_text, '&Aacute;', 'Á');
    SET output_text = REPLACE(output_text, '&Agrave;', 'À');
    SET output_text = REPLACE(output_text, '&Acirc;', 'Â');
    SET output_text = REPLACE(output_text, '&Atilde;', 'Ã');
    SET output_text = REPLACE(output_text, '&Auml;', 'Ä');
    SET output_text = REPLACE(output_text, '&Aring;', 'Å');
    
    -- Accented E
    SET output_text = REPLACE(output_text, '&eacute;', 'é');
    SET output_text = REPLACE(output_text, '&egrave;', 'è');
    SET output_text = REPLACE(output_text, '&ecirc;', 'ê');
    SET output_text = REPLACE(output_text, '&euml;', 'ë');
    SET output_text = REPLACE(output_text, '&Eacute;', 'É');
    SET output_text = REPLACE(output_text, '&Egrave;', 'È');
    SET output_text = REPLACE(output_text, '&Ecirc;', 'Ê');
    SET output_text = REPLACE(output_text, '&Euml;', 'Ë');
    
    -- Accented I
    SET output_text = REPLACE(output_text, '&iacute;', 'í');
    SET output_text = REPLACE(output_text, '&igrave;', 'ì');
    SET output_text = REPLACE(output_text, '&icirc;', 'î');
    SET output_text = REPLACE(output_text, '&iuml;', 'ï');
    SET output_text = REPLACE(output_text, '&Iacute;', 'Í');
    SET output_text = REPLACE(output_text, '&Igrave;', 'Ì');
    SET output_text = REPLACE(output_text, '&Icirc;', 'Î');
    SET output_text = REPLACE(output_text, '&Iuml;', 'Ï');
    
    -- Accented O
    SET output_text = REPLACE(output_text, '&oacute;', 'ó');
    SET output_text = REPLACE(output_text, '&ograve;', 'ò');
    SET output_text = REPLACE(output_text, '&ocirc;', 'ô');
    SET output_text = REPLACE(output_text, '&otilde;', 'õ');
    SET output_text = REPLACE(output_text, '&ouml;', 'ö');
    SET output_text = REPLACE(output_text, '&Oacute;', 'Ó');
    SET output_text = REPLACE(output_text, '&Ograve;', 'Ò');
    SET output_text = REPLACE(output_text, '&Ocirc;', 'Ô');
    SET output_text = REPLACE(output_text, '&Otilde;', 'Õ');
    SET output_text = REPLACE(output_text, '&Ouml;', 'Ö');
    
    -- Accented U
    SET output_text = REPLACE(output_text, '&uacute;', 'ú');
    SET output_text = REPLACE(output_text, '&ugrave;', 'ù');
    SET output_text = REPLACE(output_text, '&ucirc;', 'û');
    SET output_text = REPLACE(output_text, '&uuml;', 'ü');
    SET output_text = REPLACE(output_text, '&Uacute;', 'Ú');
    SET output_text = REPLACE(output_text, '&Ugrave;', 'Ù');
    SET output_text = REPLACE(output_text, '&Ucirc;', 'Û');
    SET output_text = REPLACE(output_text, '&Uuml;', 'Ü');
    
    -- Accented Y
    SET output_text = REPLACE(output_text, '&yacute;', 'ý');
    SET output_text = REPLACE(output_text, '&yuml;', 'ÿ');
    SET output_text = REPLACE(output_text, '&Yacute;', 'Ý');
    
    -- Special letters
    SET output_text = REPLACE(output_text, '&ntilde;', 'ñ');
    SET output_text = REPLACE(output_text, '&Ntilde;', 'Ñ');
    SET output_text = REPLACE(output_text, '&ccedil;', 'ç');
    SET output_text = REPLACE(output_text, '&Ccedil;', 'Ç');
    SET output_text = REPLACE(output_text, '&szlig;', 'ß');
    SET output_text = REPLACE(output_text, '&aelig;', 'æ');
    SET output_text = REPLACE(output_text, '&AElig;', 'Æ');
    SET output_text = REPLACE(output_text, '&oslash;', 'ø');
    SET output_text = REPLACE(output_text, '&Oslash;', 'Ø');
    
    -- Common numeric entities
    SET output_text = REPLACE(output_text, '&#34;', '"');
    SET output_text = REPLACE(output_text, '&#38;', '&');
    SET output_text = REPLACE(output_text, '&#60;', '<');
    SET output_text = REPLACE(output_text, '&#62;', '>');
    SET output_text = REPLACE(output_text, '&#160;', ' ');
    SET output_text = REPLACE(output_text, '&#169;', '©');
    SET output_text = REPLACE(output_text, '&#174;', '®');
    SET output_text = REPLACE(output_text, '&#8211;', '–');
    SET output_text = REPLACE(output_text, '&#8212;', '—');
    SET output_text = REPLACE(output_text, '&#8216;', "'");
    SET output_text = REPLACE(output_text, '&#8217;', "'");
    SET output_text = REPLACE(output_text, '&#8220;', '"');
    SET output_text = REPLACE(output_text, '&#8221;', '"');
    SET output_text = REPLACE(output_text, '&#8230;', '…');
    SET output_text = REPLACE(output_text, '&#8364;', '€');
    
    RETURN output_text;
END$$
DELIMITER ;

SELECT
    s.name,
    decode_html_entities(s.name) nice_name
FROM mhl_suppliers s
WHERE
  name REGEXP '&[^\\s]*;'
LIMIT 25;


-- table solution
CREATE TABLE html_entities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entity VARCHAR(20) NOT NULL COLLATE utf8mb4_bin,
    replacement VARCHAR(10) NOT NULL,
    category VARCHAR(50),
    sort_order INT,
    UNIQUE KEY (entity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin; 

INSERT INTO html_entities (entity, replacement, category, sort_order) VALUES
-- Basic entities (MUST be first)
('&amp;', '&', 'Basic', 1),
('&quot;', '"', 'Basic', 2),
('&apos;', "'", 'Basic', 3),
('&#39;', "'", 'Basic', 4),
('&lt;', '<', 'Basic', 5),
('&gt;', '>', 'Basic', 6),
('&nbsp;', ' ', 'Basic', 7),

-- Special characters
('&copy;', '©', 'Special', 10),
('&reg;', '®', 'Special', 11),
('&trade;', '™', 'Special', 12),
('&euro;', '€', 'Currency', 13),
('&pound;', '£', 'Currency', 14),
('&yen;', '¥', 'Currency', 15),
('&cent;', '¢', 'Currency', 16),

-- Math symbols
('&plusmn;', '±', 'Math', 20),
('&times;', '×', 'Math', 21),
('&divide;', '÷', 'Math', 22),
('&frac14;', '¼', 'Math', 23),
('&frac12;', '½', 'Math', 24),
('&frac34;', '¾', 'Math', 25),
('&deg;', '°', 'Math', 26),

-- Punctuation
('&ndash;', '–', 'Punctuation', 30),
('&mdash;', '—', 'Punctuation', 31),
('&lsquo;', "'", 'Punctuation', 32),
('&rsquo;', "'", 'Punctuation', 33),
('&ldquo;', '"', 'Punctuation', 34),
('&rdquo;', '"', 'Punctuation', 35),
('&hellip;', '…', 'Punctuation', 36),
('&bull;', '•', 'Punctuation', 37),
('&middot;', '·', 'Punctuation', 38),

-- Accented A
('&aacute;', 'á', 'Accented', 40),
('&agrave;', 'à', 'Accented', 41),
('&acirc;', 'â', 'Accented', 42),
('&atilde;', 'ã', 'Accented', 43),
('&auml;', 'ä', 'Accented', 44),
('&aring;', 'å', 'Accented', 45),
('&Aacute;', 'Á', 'Accented', 46),
('&Agrave;', 'À', 'Accented', 47),
('&Acirc;', 'Â', 'Accented', 48),
('&Atilde;', 'Ã', 'Accented', 49),
('&Auml;', 'Ä', 'Accented', 50),
('&Aring;', 'Å', 'Accented', 51),

-- Accented E
('&eacute;', 'é', 'Accented', 52),
('&egrave;', 'è', 'Accented', 53),
('&ecirc;', 'ê', 'Accented', 54),
('&euml;', 'ë', 'Accented', 55),
('&Eacute;', 'É', 'Accented', 56),
('&Egrave;', 'È', 'Accented', 57),
('&Ecirc;', 'Ê', 'Accented', 58),
('&Euml;', 'Ë', 'Accented', 59),

-- Accented I
('&iacute;', 'í', 'Accented', 60),
('&igrave;', 'ì', 'Accented', 61),
('&icirc;', 'î', 'Accented', 62),
('&iuml;', 'ï', 'Accented', 63),
('&Iacute;', 'Í', 'Accented', 64),
('&Igrave;', 'Ì', 'Accented', 65),
('&Icirc;', 'Î', 'Accented', 66),
('&Iuml;', 'Ï', 'Accented', 67),

-- Accented O
('&oacute;', 'ó', 'Accented', 70),
('&ograve;', 'ò', 'Accented', 71),
('&ocirc;', 'ô', 'Accented', 72),
('&otilde;', 'õ', 'Accented', 73),
('&ouml;', 'ö', 'Accented', 74),
('&Oacute;', 'Ó', 'Accented', 75),
('&Ograve;', 'Ò', 'Accented', 76),
('&Ocirc;', 'Ô', 'Accented', 77),
('&Otilde;', 'Õ', 'Accented', 78),
('&Ouml;', 'Ö', 'Accented', 79),

-- Accented U
('&uacute;', 'ú', 'Accented', 80),
('&ugrave;', 'ù', 'Accented', 81),
('&ucirc;', 'û', 'Accented', 82),
('&uuml;', 'ü', 'Accented', 83),
('&Uacute;', 'Ú', 'Accented', 84),
('&Ugrave;', 'Ù', 'Accented', 85),
('&Ucirc;', 'Û', 'Accented', 86),
('&Uuml;', 'Ü', 'Accented', 87),

-- Accented Y
('&yacute;', 'ý', 'Accented', 88),
('&yuml;', 'ÿ', 'Accented', 89),
('&Yacute;', 'Ý', 'Accented', 90),

-- Special letters
('&ntilde;', 'ñ', 'Special Letters', 91),
('&Ntilde;', 'Ñ', 'Special Letters', 92),
('&ccedil;', 'ç', 'Special Letters', 93),
('&Ccedil;', 'Ç', 'Special Letters', 94),
('&szlig;', 'ß', 'Special Letters', 95),
('&aelig;', 'æ', 'Special Letters', 96),
('&AElig;', 'Æ', 'Special Letters', 97),
('&oslash;', 'ø', 'Special Letters', 98),
('&Oslash;', 'Ø', 'Special Letters', 99),

-- Numeric entities
('&#34;', '"', 'Numeric', 100),
('&#38;', '&', 'Numeric', 101),
('&#60;', '<', 'Numeric', 102),
('&#62;', '>', 'Numeric', 103),
('&#160;', ' ', 'Numeric', 104),
('&#169;', '©', 'Numeric', 105),
('&#174;', '®', 'Numeric', 106),
('&#8211;', '–', 'Numeric', 107),
('&#8212;', '—', 'Numeric', 108),
('&#8216;', "'", 'Numeric', 109),
('&#8217;', "'", 'Numeric', 110),
('&#8220;', '"', 'Numeric', 111),
('&#8221;', '"', 'Numeric', 112),
('&#8230;', '…', 'Numeric', 113),
('&#8364;', '€', 'Numeric', 114);

WITH RECURSIVE entity_replacement AS (
    SELECT 
        s.name,
        s.name nice_name,
        1 entity_index
    FROM (
        SELECT name
        FROM mhl_suppliers
        WHERE name REGEXP '&[^\\s]*;'
        LIMIT 25
    ) s
    
    UNION ALL
    
    SELECT 
        er.name,
        REPLACE(er.nice_name, he.entity, he.replacement) nice_name,
        er.entity_index + 1
    FROM entity_replacement er
    JOIN (
        SELECT 
            entity, 
            replacement, 
            ROW_NUMBER() OVER (ORDER BY sort_order) idx
        FROM html_entities
    ) he ON he.idx = er.entity_index
)
SELECT 
    name,
    nice_name
FROM entity_replacement
WHERE entity_index = (SELECT COUNT(*) FROM html_entities)