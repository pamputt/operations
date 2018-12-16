#!/bin/bash
### QUERY STRING, key parts for later use.
queryCore="?record prop:P2 entity:Q2 ;
          prop:P3 ?file ;
          prop:P5 ?locutor ;
          prop:P7 ?transcription ;
          prop:P6 ?date ;
          prop:P4 ?lang.
  ?locutor prop:P11 ?user.

  OPTIONAL{ ?lang prop:P13 ?langIso. }
  OPTIONAL { ?record prop:P18 ?qualif. }

  ?locutor rdfs:label ?locutorLabel.
  ?lang rdfs:label ?langLabel FILTER (lang(?langLabel) = 'en')."
queryBinds="
  BIND( CONCAT(
    SUBSTR(STR(?lang), 31),
    IF(BOUND(?langIso), CONCAT('_(', ?langIso, ')'), '_(no_langIso)'),
    IF(BOUND(?langLabel), CONCAT('_(', ?langLabel, ')'), '_(no_langLabel)')
  ) AS ?langstr)
  BIND( IF( STR(?locutorLabel) = ?user, '', CONCAT( ' (', ?locutorLabel, ')' ) ) AS ?locutorstr )
  BIND( IF(BOUND(?qualif), CONCAT(' (', ?qualif, ')'), '') AS ?qualifstr )
  BIND(CONCAT(?langstr, '/', ?user, ?locutorstr, '/', ?transcription, ?qualifstr, '.wav') AS ?filename)
"
queryFocusPeriod=`date --rfc-3339=date --date="- 2 days"`
queryFilterRecent="FILTER( ?date > \"$queryFocusPeriod\"^^xsd:dateTime )."
query="$queryCore $queryBinds"

### DOWNLOAD VIA CommonsDownloadTool & QUERY
mkdir -p  /tmp/datasets /tmp/datasets/raw/

echo "--> Force the download of recent changes..."
/usr/bin/python3.6 /home/www/CommonsDownloadTool/commons_download_tool.py --keep --sparqlurl https://lingualibre.fr/bigdata/namespace/wdq/sparql --sparql "SELECT ?file ?filename WHERE { ${query} ${queryFilterRecent} }" --threads 4 --directory  /tmp/datasets/raw/ --forcedownload --nozip --fileformat ogg

echo "--> Download and package ALL sounds..."
/usr/bin/python3.5 /home/www/CommonsDownloadTool/commons_download_tool.py --keep --sparqlurl https://lingualibre.fr/bigdata/namespace/wdq/sparql --sparql "SELECT ?file ?filename WHERE { ${query} }" --threads 4 --directory  /tmp/datasets/raw/ --output "/tmp/datasets/ALL.zip" --fileformat ogg

echo "--> Package each language individually"
for qidpath in  /tmp/datasets/raw/*;
do
  qid=$(echo ${qidpath##*/} | cut -d'_' -f 1)
  if [[ $qid == Q* ]] ; then
    echo "--> Processing ${qid}..."
    /usr/bin/python3.5 /home/www/CommonsDownloadTool/commons_download_tool.py --keep --sparqlurl https://lingualibre.fr/bigdata/namespace/wdq/sparql --sparql "SELECT ?file ?filename WHERE { ${query} ?record prop:P4 entity:${qid}. }" --threads 4 --directory  /tmp/datasets/raw/ --output "/tmp/datasets/${qidpath}.zip" --fileformat ogg
  fi
done

### MOVE TO PUBLIC FOLDER
echo "--> Move zip archives in the public area, ALL and by language"
for filename in  /tmp/datasets/*.zip;
do
  zip_basename="$(basename "$filename")"
  mv "$filename" "/home/www/datasets/LinguaLibre-$zip_basename";
done;
