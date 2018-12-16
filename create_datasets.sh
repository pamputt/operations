#!/bin/bash

query="?record prop:P2 entity:Q2 ;
          prop:P3 ?file ;
          prop:P5 ?locutor ;
          prop:P7 ?transcription ;
          prop:P6 ?date ;
          prop:P4 ?lang.
  ?locutor prop:P11 ?user.
  
  OPTIONAL{ ?lang prop:P13 ?langiso. }
  OPTIONAL { ?record prop:P18 ?qualif. }
  
  ?locutor rdfs:label ?locutorLabel.
  BIND( CONCAT(SUBSTR(STR(?lang), 31), IF(BOUND(?langiso), CONCAT(' (', ?langiso, ')'), '')) AS ?langstr)
  BIND( IF( STR(?locutorLabel) = ?user, '', CONCAT( ' (', ?locutorLabel, ')' ) ) AS ?locutorstr )
  BIND( IF(BOUND(?qualif), CONCAT(' (', ?qualif, ')'), '') AS ?qualifstr )
  BIND(CONCAT(?langstr, '/', ?user, ?locutorstr, '/', ?transcription, ?qualifstr, '.wav') AS ?filename)
"



mkdir -p /tmp/datasets

echo "--> Force the download of recent changes..."
/usr/bin/python3.5 /home/www/CommonsDownloadTool/commons_download_tool.py --keep --sparqlurl https://lingualibre.fr/bigdata/namespace/wdq/sparql --sparql "SELECT ?file ?filename WHERE { ${query} FILTER( ?date > \"`date --rfc-3339=date --date="- 2 days"`\"^^xsd:dateTime ). }" --threads 4 --directory /tmp/datasets/raw/ --forcedownload --nozip --fileformat ogg

echo "--> Download and package all sounds..."
/usr/bin/python3.5 /home/www/CommonsDownloadTool/commons_download_tool.py --keep --sparqlurl https://lingualibre.fr/bigdata/namespace/wdq/sparql --sparql "SELECT ?file ?filename WHERE { ${query} }" --threads 4 --directory /tmp/datasets/raw/ --output "/tmp/datasets/LinguaLibre-ALL.zip" --fileformat ogg

echo "--> Move the global zip in the public area"
mv /tmp/datasets/LinguaLibre-ALL.zip /home/www/datasets/

echo "--> Packahe each language individually"
for qidpath in /tmp/datasets/raw/*;
do
  qid=$(echo ${qidpath##*/} | cut -d' ' -f 1)
  if [[ $qid == Q* ]] ; then
    echo "--> Processing ${qid}..."
    /usr/bin/python3.5 /home/www/CommonsDownloadTool/commons_download_tool.py --keep --sparqlurl https://lingualibre.fr/bigdata/namespace/wdq/sparql --sparql "SELECT ?file ?filename WHERE { ${query} ?record prop:P4 entity:${qid}. }" --threads 4 --directory /tmp/datasets/raw/ --output "/tmp/datasets/${qid}.zip" --fileformat ogg
    mv "/tmp/datasets/${qid}.zip" "/home/www/datasets/LinguaLibre-${qidpath}.zip"
  fi
done
