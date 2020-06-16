#!/bin/bash

### Sparql query.
query="?record prop:P2 entity:Q2 ;
          prop:P3 ?file ;
          prop:P5 ?locutor ;
          prop:P7 ?transcription ;
          prop:P6 ?date ;
          prop:P4 ?lang.
  ?locutor prop:P11 ?user.

  OPTIONAL{ ?lang prop:P13 ?langIso. }
  OPTIONAL { ?record prop:P18 ?qualif. }

  ?locutor rdfs:label ?locutorLabel.
  ?lang rdfs:label ?langLabel FILTER (lang(?langLabel) = 'en').

  BIND( CONCAT(
    SUBSTR(STR(?lang), 31),
    '-',
    IF(BOUND(?langIso), ?langIso, 'mis'),
    IF(BOUND(?langLabel), CONCAT('-', ?langLabel), '')
  ) AS ?langstr)
  BIND( IF( STR(?locutorLabel) = ?user, '', CONCAT( ' (', ?locutorLabel, ')' ) ) AS ?locutorstr )
  BIND( IF(BOUND(?qualif), CONCAT(' (', ?qualif, ')'), '') AS ?qualifstr )
  BIND(CONCAT(?langstr, '/', ?user, ?locutorstr, '/', ?transcription, ?qualifstr, '.wav') AS ?filename)
"
queryFilterRecent="FILTER( ?date > \"`date --rfc-3339=date --date=\"- 5 days\"`\"^^xsd:dateTime )."

# Create (if it doesn't exist already) a temp folder for all the records we will download
mkdir -p  /tmp/datasets /tmp/datasets/raw/

# Download all recently (> 2 days) recorded files
# We --forcedownload  the recently uploaded files only,
# to get the newer version of those that have been updated since last run
echo "--> Force the download of recent changes..."
/usr/bin/python3.5 /home/www/CommonsDownloadTool/commons_download_tool.py --keep --sparqlurl https://lingualibre.org/bigdata/namespace/wdq/sparql --sparql "SELECT ?file ?filename WHERE { ${query} ${queryFilterRecent} }" --threads 4 --directory  /tmp/datasets/raw/ --forcedownload --nozip --fileformat ogg

# Check that we have all the files (or download them) and zip everything
# We could use zip directly (as the previous command also ensure we got every new files),
# but by doing it this way deleted files on Lingua Libre won't be shipped even if cached.
echo "--> Download and package ALL sounds..."
/usr/bin/python3.5 /home/www/CommonsDownloadTool/commons_download_tool.py --keep --sparqlurl https://lingualibre.org/bigdata/namespace/wdq/sparql --sparql "SELECT ?file ?filename WHERE { ${query} }" --threads 4 --directory  /tmp/datasets/raw/ --output "/tmp/datasets/lingualibre_full.zip" --fileformat ogg

# Package each language individually
echo "--> Package each language individually"
for qidpath in  /tmp/datasets/raw/*;
do
  qid=$(echo ${qidpath##*/} | cut -d'-' -f 1)
  if [[ $qid == Q* ]] ; then
    echo "--> Processing ${qid}..."
    /usr/bin/python3.5 /home/www/CommonsDownloadTool/commons_download_tool.py --keep --sparqlurl https://lingualibre.org/bigdata/namespace/wdq/sparql --sparql "SELECT ?file ?filename WHERE { ${query} ?record prop:P4 entity:${qid}. }" --threads 4 --directory  /tmp/datasets/raw/ --output "/tmp/datasets/${qidpath##*/}.zip" --fileformat ogg
  fi
done

### MOVE TO PUBLIC FOLDER
echo "--> Move all zip archives in the public datasets folder"
mv /tmp/datasets/*.zip /home/www/datasets/
