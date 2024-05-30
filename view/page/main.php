<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI-Devs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="view\css\style.css">

</head>

<body>
    <?php echo __FILE__ ?>
    <div class="row">
        <div class="col-md-6">
            <form action="/" method="GET" id=taskForm>
                <div class="mb-3">
                    <label for="formGroupExampleInput" class="form-label">Example label</label>
                    <input type="text" class="form-control w-75" id="formGroupExampleInput" placeholder="Example input placeholder">
                </div>
                <div class="mb-3">
                    <label for="formGroupExampleInput2" class="form-label">Another label</label>
                    <input type="text" class="form-control w-75" id="formGroupExampleInput2" placeholder="Another input placeholder">
                </div>

                <label for="formGroupExampleInput2" class="form-label">Task</label>
                <select class="form-select w-75 mb-3" name="selectedTask" id=taskSelect  aria-label="Default select example">
                    <option selected>select task</option>
                    <option value="helloapi">helloapi</option>
                    <option value="moderation">moderation</option>
                    <option value="blogger">blogger</option>
                </select>

                <button type="submit" class="btn btn-primary ">Primary</button>
            </form>
        </div>

        <div class="col-md-6">
            WYNIKI:
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="view/js/script.js"> </script>

</body>

</html>