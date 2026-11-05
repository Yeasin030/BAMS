<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create company</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Arial, Helvetica, sans-serif;
            color: #1e293b;
            background: #f4f7fb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 48px 20px;
        }

        .form-card {
            width: min(100%, 760px);
            margin: 0 auto;
            padding: 32px;
            background: #fff;
            border: 1px solid #e4eaf2;
            border-radius: 16px;
            box-shadow: 0 8px 28px rgba(30, 50, 80, .07);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 26px;
        }

        .intro {
            margin: 0 0 28px;
            color: #718096;
            font-size: 14px;
            line-height: 1.5;
        }

        .fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .field {
            min-width: 0;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 700;
        }

        .required {
            color: #d33b4b;
        }

        input, textarea, select {
            width: 100%;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid #dce3ed;
            border-radius: 8px;
            background: #fff;
            color: #1e293b;
            font: inherit;
            font-size: 14px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus, textarea:focus, select:focus {
            outline: 3px solid rgba(53, 109, 243, .14);
            border-color: #7095f7;
        }

        .hint {
            margin: 7px 0 0;
            color: #8491a3;
            font-size: 12px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e9edf3;
        }

        button {
            min-height: 42px;
            padding: 0 17px;
            border: 1px solid #dce3ed;
            border-radius: 8px;
            background: #fff;
            color: #344256;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        button[type="submit"] {
            border-color: #356df3;
            background: #356df3;
            color: #fff;
        }

        button[type="submit"]:hover {
            border-color: #2558d7;
            background: #2558d7;
        }

        @media (max-width: 560px) {
            body {
                padding: 20px 12px;
            }

            .form-card {
                padding: 22px 18px;
            }

            .fields {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .field.full {
                grid-column: auto;
            }
        }
    </style>
</head>
<body>
    <main class="form-card">
        <h1>Create company</h1>
        <p class="intro">Enter the company details below. Fields marked with <span class="required">*</span> are required.</p>

        <form method="post" enctype="multipart/form-data">
            <div class="fields">
                <div class="field full">
                    <label for="company_name">Company name <span class="required">*</span></label>
                    <input type="text" id="company_name" name="company_name" maxlength="150" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" maxlength="150">
                </div>

                <div class="field">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" maxlength="30">
                </div>

                <div class="field full">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"></textarea>
                </div>

                <div class="field">
                    <label for="logo">Logo</label>
                    <input type="file" id="logo" name="logo" accept="image/*">
                </div>

                <div class="field">
                    <label for="currency">Currency <span class="required">*</span></label>
                    <input type="text" id="currency" name="currency" maxlength="10" value="BDT" required>
                </div>

                <div class="field">
                    <label for="financial_year_start">Financial year start date</label>
                    <input type="date" id="financial_year_start" name="financial_year_start">
                </div>

                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="reset">Clear</button>
                <button type="submit">Create company</button>
            </div>
        </form>
    </main>
</body>
</html>
