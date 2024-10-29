<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interactive File Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --hover-color: #4338ca;
            --border-color: #e5e7eb;
            --bg-color: #f9fafb;
        }

        body {
            background-color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .upload-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            padding: 2rem;
        }

        .title {
            color: #111827;
            font-weight: 600;
            margin-bottom: 2rem;
            position: relative;
        }

        .title::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .file-upload {
            border: 2px dashed var(--border-color);
            border-radius: 1rem;
            padding: 2.5rem;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
            background: var(--bg-color);
        }

        .file-upload:hover {
            border-color: var(--primary-color);
        }

        .file-upload.dragover {
            background-color: #eef2ff;
            border-color: var(--primary-color);
            transform: scale(1.02);
        }

        .upload-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .browse-btn {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            margin-top: 1rem;
        }

        .browse-btn:hover {
            background: var(--hover-color);
            transform: translateY(-2px);
        }

        .file-upload-preview {
            display: none;
            margin-top: 2rem;
            padding: 1rem;
            border-radius: 0.5rem;
            background: var(--bg-color);
        }

        .file-upload-preview img {
            max-width: 150px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgb(0 0 0 / 0.1);
        }

        .submit-btn {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            width: 100%;
            margin-top: 1.5rem;
        }

        .submit-btn:hover {
            background: var(--hover-color);
            transform: translateY(-2px);
        }

        .upload-text {
            color: #6b7280;
            margin: 1rem 0;
        }

        .file-info {
            margin-top: 1rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .success-message {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            background: #ecfdf5;
            color: #065f46;
            text-align: center;
        }

        .error-message {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            background: #fef2f2;
            color: #991b1b;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="upload-container">
                    <h2 class="title text-center">Upload Your File</h2>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div id="fileUpload" class="file-upload">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <p class="upload-text">Drag and drop your file here, or</p>
                            <label for="customFile" class="browse-btn">
                                <i class="fas fa-folder-open me-2"></i>Browse Files
                            </label>
                            <input type="file" name="fileToUpload" class="form-control d-none" id="customFile" accept="image/*">
                            <p class="file-info">Supported formats: PNG, JPG, GIF</p>
                        </div>

                        <div class="file-upload-preview">
                            <h5 class="mb-3">Selected File:</h5>
                            <div class="text-center">
                                <img id="previewImage" src="#" alt="File Preview" class="mb-3">
                                <p id="filePath" class="text-muted mb-0"></p>
                            </div>
                        </div>

                        <button type="submit" name="submit" class="submit-btn">
                            <i class="fas fa-upload me-2"></i>Upload File
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const fileUpload = document.getElementById("fileUpload");
        const customFile = document.getElementById("customFile");
        const previewContainer = document.querySelector(".file-upload-preview");
        const previewImage = document.getElementById("previewImage");
        const filePath = document.getElementById("filePath");

        fileUpload.addEventListener("click", (e) => {
            if (e.target.classList.contains('browse-btn')) {
                customFile.click();
            }
        });

        fileUpload.addEventListener("dragover", (event) => {
            event.preventDefault();
            fileUpload.classList.add("dragover");
        });

        fileUpload.addEventListener("dragleave", () => fileUpload.classList.remove("dragover"));

        fileUpload.addEventListener("drop", (event) => {
            event.preventDefault();
            fileUpload.classList.remove("dragover");
            customFile.files = event.dataTransfer.files;
            previewFile(customFile.files[0]);
        });

        customFile.addEventListener("change", () => {
            if (customFile.files.length) {
                previewFile(customFile.files[0]);
            }
        });

        function previewFile(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewContainer.style.display = "block";
                previewImage.src = e.target.result;
                filePath.textContent = `Selected: ${file.name}`;
            };
            reader.readAsDataURL(file);
        }
    </script>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
        $target_dir = "uploads/";

        $file_name = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_FILENAME);
        $file_extension = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
        $unique_file_name = $file_name . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $unique_file_name;

        $uploadOk = 1;
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);


        if ($check !== false) {
            echo "<div class='success-message'><i class='fas fa-check-circle me-2'></i>File is an image - " . $check["mime"] . ".</div>";
            $uploadOk = 1;
        } else {
            echo "<div class='error-message'><i class='fas fa-exclamation-circle me-2'></i>File is not an image.</div>";
            $uploadOk = 0;
        }


        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "<div class='success-message'><i class='fas fa-check-circle me-2'></i>File uploaded successfully: <a href='$target_file'>$unique_file_name</a></div>";
            } else {
                echo "<div class='error-message'><i class='fas fa-exclamation-circle me-2'></i>Sorry, there was an error uploading your file.</div>";
            }
        }
    }
    ?>

    ?>
</body>

</html>