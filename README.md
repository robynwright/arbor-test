
# Arbor Word Puzzle Test

A Laravel (PHP 8.2+) backend for a word puzzle game where students create valid English words from a random string of letters.

---

## Getting Started

### Clone the Repository

```bash

git  clone  https://github.com/robynwright/arbor-test.git

cd  arbor-test

```

### Create a .env file by copying the example:

```bash

cp  .env.example  .env

```

### Set up the Database

```bash

php artisan key:generate

touch database/database.sqlite

php artisan migrate --seed

```
  
### Set up the word list

```bash

mv words.txt storage/app/ 

```

### Run the Server

```bash

php  artisan  serve

```

### Open in Your Browser

Visit the following URL:

```

http://127.0.0.1:8000

```

---

## Notes

-   I chose Laravel for its ease of setup and the ability to quickly scaffold migrations, models, and controllers using artisan commands.
-   The student login is based on seeded data: 20 fake students are added to the database during initial migration/seeding for easy testing.
-   To generate puzzles, I downloaded a list of valid English words from the [dwyl/english-words](https://github.com/dwyl/english-words) GitHub repo.
-   The puzzle string is created by randomly selecting 4 valid English words, merging and shuffling their letters, ensuring all puzzles are solvable.   
-   Word validation during play also uses the same word list for consistency.
-   Puzzle state is tracked in the session — including guessed words and used letters — to maintain game flow.
-   Submissions are saved to the database with a link to the puzzle and student for accurate scoring and leaderboard tracking.
-   The finish screen checks for remaining valid words and displays them alongside the player's final score.
-   A public Top 10 leaderboard shows the highest scoring students and the puzzle string they played with.

## Questions/Assumptions made

- Assumed valid English words come exclusively from the provided `words.txt` list.
- Assumed user authentication was out of scope; any seeded student ID is accepted to log in. The focus was on core puzzle functionality rather than login mechanics.
- Assumed all users are students. Admin or teacher roles were not considered within the scope of this exercise.

## Small changes I would do to improve the system

- Implement a full student authentication system with login and registration.
- Use AJAX for word submission to improve UX and avoid full page reloads.
- Find a more appopriate list of english words as the selected list does seem to have names includes
