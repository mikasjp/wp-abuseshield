import os
import zipfile

def zipdir(path, ignored, ziph):
    # ziph is zipfile handle
    for root, dirs, files in os.walk(path):
        for file in files:
            if file not in ignored:
                ziph.write(os.path.join(root, file))

zipf = zipfile.ZipFile('wp-abuseshield.zip', 'w', zipfile.ZIP_DEFLATED)
ignored = ["build.py", ".gitignore", "HowDoesItWork_bad.png", "HowDoesItWork_good.png", "README.md"]
zipdir("./", ignored, zipf)
zipf.close()
