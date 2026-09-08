# Project Structure & Contribution Guidelines

This document outlines the standard folder structure, naming conventions, API request methods, and git ignore rules for the project.

## 1. Feature-Based Folder Structure

All new features and their corresponding API endpoints must be placed inside the `user_api` directory. Instead of having a flat structure, we group files by feature into their own dedicated folders.

**Convention:** `user_api/[feature]/[action].php`

For example, if you are working on the `user` feature, the folder structure should look like this:
- `user_api/user/index.php` (Read all users)
- `user_api/user/create.php` (Create a new user)
- `user_api/user/delete.php` (Delete a user)

If a feature is nested, such as a user under properties, use a nested structure:
- `user_api/properties/user/create.php`

## 2. HTTP Request Methods

We enforce strict HTTP method checking depending on the action being performed. You must check that the correct request method is used before executing the action.

- **POST Requests:** Use POST for any action that mutates data. This includes:
  - Creating records (`create.php`)
  - Editing/Updating records
  - Deleting records (`delete.php`)
- **GET Requests:** Use GET strictly for reading or fetching data (`index.php`, `read.php`).

**Example Check (PHP):**
```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed. Expected POST."]);
    exit;
}
```

## 3. Git Ignore Rules

To prevent developers from accidentally committing local test images, our `.gitignore` strategy is scoped to the `images/` folder.

### How Git Ignores Files:
Git uses `.gitignore` to prevent untracked files from being added to the repository. 
**Crucial Note:** If a file is *already* tracked by Git (i.e., it has already been committed to the repository), adding an ignore rule will **not** remove it or stop it from being tracked. The `.gitignore` only applies to *new, untracked files*.

### Our Project Rules:
We have an `images/` folder that contains default images already uploaded to the server and tracked by Git. We want to keep tracking those existing files, but we want to ignore any *new* images uploaded by developers during local testing.

To achieve this, we placed a `.gitignore` file **inside** the `images/` folder with the following rules:

```gitignore
*.jpg
*.jpeg
*.png
*.gif
*.svg
```

Because Git continues to track already-committed files, these rules will only ignore *newly added* test images in the `images/` folder, keeping the repository clean without breaking the existing server files.
