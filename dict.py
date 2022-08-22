from zipfile import ZipFile
import os
import json
import mysql.connector
import urllib.request
import urllib.error
from pathlib import Path



CURRENT_PATH = os.path.dirname(os.path.realpath(__file__))
DATA_PATH = os.path.join(CURRENT_PATH)
NIST_URL = "https://nvd.nist.gov/feeds/json/cve/1.1/"
NIST_URL_FIN = "nvdcve-1.1-modified.json.zip"

path_to_zip = 'extract/nvdcve-1.1-modified.json.zip'

path = Path(path_to_zip)

if path.is_file():
    os.remove ('extract/nvdcve-1.1-modified.json.zip')
    os.remove ('nvdcve-1.1-modified.json')
    print('Ancien json supprimé')
else:
    print('Aucun fichier à supprimer')

def download_all_nist():

   # modified_meta = (NIST_URL + "nvdcve-1.1-modified.meta" )
    modified = (NIST_URL + NIST_URL_MODIFIED )
    file = os.path.join(DATA_PATH, (NIST_URL_MODIFIED))
    download_http(modified, file)

def download_http(modified, file):
    opener = urllib.request.build_opener()
    opener.addheaders = [('User-agent', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36 SE 2.X MetaSr 1.0')]
#     user-agent à changer 
    urllib.request.install_opener(opener)
    try:
        urllib.request.urlretrieve(modified, file, progress)
    except urllib.error.HTTPError as e:
        print("impossible de charger '{}'. '{}' '{}'".format(modified, e.getcode(), e.reason))
    with ZipFile(file, 'r') as zip:

        # extraire tous les fichiers
        zip.extractall()
        print('Extraction du ZIP bien effectué')

def progress(count, block_size, total_size):
    print("\r'{}':'{}':'{}'".format(count, block_size, total_size), end='')


download_all_nist()

def extract_and_push():
    file = os.path.join(DATA_PATH, ('connection.json'))
    with open(file, encoding="utf8") as jsonfile:
        host= json.load(jsonfile)["host"]
    with open(file, encoding="utf8") as jsonfile:
        database = json.load(jsonfile)["database"]
    with open(file, encoding="utf8") as jsonfile:
        user = json.load(jsonfile)["user"]
    with open(file, encoding="utf8") as jsonfile:
        password = json.load(jsonfile)["password"]
    db = mysql.connector.connect(
            host= host,
            database= database ,
            user= user ,
            password= password
            )

    cursor = db.cursor()

    if db.is_connected():
        print("base de donnee fonctionnelle")
    else:
        print("erreur dans bdd ")

    file = os.path.join(DATA_PATH, ('nvdcve-1.1-modified.json'))
    with open(file, encoding="utf8") as jsonfile:
        cves = json.load(jsonfile)["CVE_Items"]
        print(len(cves))
        for cve in cves :
            try:
                #data.clean()
                cve_id = cve["cve"]["CVE_data_meta"]["ID"]
                impact = cve["impact"]
                
                try:
                    value = cve["cve"]["description"]["description_data"][0]["value"]
                except:
                    None
                try:
                    publishedDate = cve["publishedDate"]
                    lastModifiedDate = cve["lastModifiedDate"]    
                    
                except:
                    None
                try:
                    cpe23Uri = cve["configurations"]["nodes"][0]["cpe_match"][0]["cpe23Uri"]
                    versionStartIncluding = cve["configurations"]["nodes"][0]["cpe_match"][0]["versionStartIncluding"]
                except:
                    None
                try:
                    base = impact["baseMetricV2"]
                    cvss2_score = base["cvssV2"]["baseScore"]
                    cvss2_exploitabilityScore = base["exploitabilityScore"]
                    cvss2_impactScore = base["impactScore"]
                except:
                    None
                try:
                    base = impact["baseMetricV3"]
                    cvss3_score = base["cvssV3"]["baseScore"]
                    cvss3_exploitabilityScore = base["exploitabilityScore"]
                    cvss3_impactScore = base["impactScore"]
                except:
                   None
                try:
                    cwe = cve["cve"]["problemtype"]["problemtype_data"][0]["description"][0]["value"]
                except:                   None

                test1= cpe23Uri
                test = test1.split (":")
                test4 = test[3]
                version = test[4]
                


                d = {
                 "cve_id": cve_id,
                 #"value" : value,
                 "cvss2_score": cvss2_score,
                 "cvss2_exploitabilityScore": cvss2_exploitabilityScore,
                 "cvss2_impactScore": cvss2_impactScore,
                 "cvss3_score": cvss3_score,
                 "cvss3_exploitabilityScore": cvss3_exploitabilityScore,
                 "cvss3_impactScore": cvss3_impactScore,
                 "cwe": cwe,
                 "publishedDate" : publishedDate[0:10],
                 "lastModifiedDate" : lastModifiedDate[0:10],
                 "value" : value,
                 "cpe23Uri" : test4,
                 "version" : version,
                 "versionStartIncluding" : versionStartIncluding,
                 
                 }

                query = ("INSERT IGNORE INTO cve (cve_id, cvss2_score, cvss2_exploitabilityScore, cvss2_impactScore, cvss3_score, cvss3_exploitabilityScore, cvss3_impactScore, cwe, publishedDate, lastModifiedDate, value, cpe23Uri, versionStartIncluding, version) VALUES (%(cve_id)s, %(cvss2_score)s, %(cvss2_exploitabilityScore)s, %(cvss2_impactScore)s , %(cvss3_score)s, %(cvss3_exploitabilityScore)s, %(cvss3_impactScore)s, %(cwe)s ,%(publishedDate)s ,%(lastModifiedDate)s, %(value)s, %(cpe23Uri)s ,%(versionStartIncluding)s, %(version)s ) ON DUPLICATE KEY UPDATE cvss2_score = %(cvss2_score)s, cvss2_exploitabilityScore = %(cvss2_exploitabilityScore)s, cvss2_impactScore = %(cvss2_impactScore)s , cvss3_score = %(cvss3_score)s, cvss3_exploitabilityScore = %(cvss3_exploitabilityScore)s, cvss3_impactScore = %(cvss3_impactScore)s, cwe = %(cwe)s,  publishedDate = %(publishedDate)s , lastModifiedDate = %(lastModifiedDate)s , value = %(value)s, cpe23Uri = %(cpe23Uri)s, versionStartIncluding = %(versionStartIncluding)s, version = %(version)s ")
                cursor.execute(query, d)
                db.commit()
                print(cve_id, "inseré")
             
            except:
               print("erreur")

    print(cursor.rowcount, "créée")
    db.commit()

if __name__ == '__main__':
    extract_and_push()

