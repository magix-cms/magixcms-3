-- mc_cms_page_content
CREATE INDEX idx_cms_page_content_lang
    ON mc_cms_page_content(id_pages, id_lang);

-- mc_cms_page_img
CREATE INDEX idx_cms_page_img_default
    ON mc_cms_page_img(id_pages, default_img);

-- mc_news_content
CREATE INDEX idx_news_content_lang
    ON mc_news_content(id_news, id_lang);

-- mc_news
CREATE INDEX idx_news_dates
    ON mc_news(date_publish, date_event_start, date_event_end);

-- mc_news_img
CREATE INDEX idx_news_img_default
    ON mc_news_img(id_news, default_img);

-- mc_news_tag_rel
CREATE INDEX idx_news_tag_rel_lookup
    ON mc_news_tag_rel(id_news, id_tag);

-- mc_catalog_cat_content : accès rapide par catégorie + langue
CREATE INDEX idx_cat_content_cat_lang
    ON mc_catalog_cat_content(id_cat, id_lang);

-- mc_catalog_product_content : accès rapide par produit + langue
CREATE INDEX idx_product_content_product_lang
    ON mc_catalog_product_content(id_product, id_lang);

-- mc_catalog_product_img : accès rapide par produit + image par défaut
CREATE INDEX idx_product_img_default
    ON mc_catalog_product_img(id_product, default_img);

-- mc_catalog_product_img_content : déjà un composite (id_img, id_lang),
-- on garde mais on ajoute aussi un index direct sur id_lang si besoin
CREATE INDEX idx_product_img_content_lang
    ON mc_catalog_product_img_content(id_lang);

-- mc_catalog : souvent utilisé pour trouver la catégorie par produit et inversement
CREATE INDEX idx_catalog_product_cat
    ON mc_catalog(id_product, id_cat);

-- mc_catalog : optimisation si on filtre sur la catégorie seule
CREATE INDEX idx_catalog_cat
    ON mc_catalog(id_cat, default_c);