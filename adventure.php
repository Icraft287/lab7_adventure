<?php
    // 1. Get user's name from cookie, default to 'Traveler' if not set
    $name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : 'Traveler';

    // 2. Get current page and choice from query string
    $page   = isset($_GET['page'])   ? $_GET['page']   : '';
    $choice = isset($_GET['choice']) ? $_GET['choice'] : '';

    // 3. Create associative arrays for each part of the story
    $beginning = [
        'title'       => 'The Mysterious Forest',
        'description' => "Welcome, $name! You find yourself at the edge of a mysterious forest. Do you dare to enter?",
        'choices'     => ['Enter the forest', 'Walk away']
    ];

    $middle = [
        'title'       => 'The Enchanted Clearing',
        'description' => ($choice === '1')
            ? "As you step into the forest, $name, you discover an enchanted clearing filled with glowing flowers. Do you explore further or rest here?"
            : "You decide to walk away, but as you turn around, $name, you find a hidden path leading to an enchanted clearing filled with glowing flowers. Do you explore further or rest here?",
        'choices'     => ['Explore Further', 'Rest here']
    ];

    $ending = [
        'title'       => 'The Journey\'s End',
        'description' => ($choice === '1')
            ? "You venture deeper into the clearing, $name, and uncover an ancient treasure hidden among the glowing flowers. Your curiosity has been rewarded!"
            : "You rest among the glowing flowers, $name, and wake up feeling magical energy flow through you. The forest has blessed you with its gift.",
        'choices'     => ['Play Again']
    ];

    // 4. Conditional logic to assign correct section based on page
    $title       = '';
    $description = '';
    $choices     = [];
    $is_error    = false;
    $is_ending   = false;
    $next_page   = '';

    if ($page === 'beginning') {
        $title       = $beginning['title'];
        $description = $beginning['description'];
        $choices     = $beginning['choices'];
        $next_page   = 'middle';

    } elseif ($page === 'middle') {
        $title       = $middle['title'];
        $description = $middle['description'];
        $choices     = $middle['choices'];
        $next_page   = 'ending';

    } elseif ($page === 'ending') {
        $title       = $ending['title'];
        $description = $ending['description'];
        $choices     = $ending['choices'];
        $is_ending   = true;

    } else {
        // Handle unexpected/missing page value
        $is_error = true;
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Your Adventure</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>

  <body>
    <div class="container" id="adventure">

      <?php if ($is_error): ?>
        <!-- Error/fallback state -->
        <h1>Oops! Something went wrong.</h1>
        <p>We couldn't find that part of the adventure.</p>
        <a href="index.php"><button>Restart</button></a>

      <?php else: ?>
        <!-- Dynamic story content -->
        <h1><?= htmlspecialchars($title) ?></h1>
        <p><?= htmlspecialchars($description) ?></p>

        <!-- Loop through choices to build buttons -->
        <?php foreach ($choices as $index => $choice_text): ?>
          <?php $choice_num = $index + 1; ?>

          <?php if ($is_ending): ?>
            <!-- Ending: Play Again goes back to index.php -->
            <a href="index.php">
              <button><?= htmlspecialchars($choice_text) ?></button>
            </a>

          <?php else: ?>
            <!-- Normal: go to next page with choice number in URL -->
            <a href="adventure.php?page=<?= $next_page ?>&choice=<?= $choice_num ?>&name=<?= urlencode($name) ?>">
              <button><?= htmlspecialchars($choice_text) ?></button>
            </a>
          <?php endif; ?>

        <?php endforeach; ?>

      <?php endif; ?>

    </div>

    <script src="js/animations.js"></script>
  </body>
</html>