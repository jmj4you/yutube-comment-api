
## Scalable Comment Management API


This project is a scalable REST API built with **Laravel 12.0**, **PHP 8.2** and **MySql 5.7**, designed to manage and user comments efficiently for a YouTube-style comment section. The API supports core functionalities like creating, retrieving, and listing/ranking comments based on engagement metrics such as likes, replies, and novelty.

The project includes:

- **REST Endpoints:** A well-structured set of endpoints for managing comments and their associated data.
- **Database Schema:** A separate `database/db-schema-yutube_comment_api.sql` file containing table and view definitions designed to support the identified use cases.
- **Eloquent ORM:** Used for seamless and efficient interaction with the database.

This API is designed to run locally and does not authentication. The architecture ensures scalability and maintainability, ready to handle growing data and evolving requirements.



Let’s break this down step by step:

## 1) Use Cases & Endpoints:

a) Comment Management:

    Add a comment → POST /comments
    Edit a comment → PUT /comments/{id}
    Delete a comment → DELETE /comments/{id}

    Fetch comments for a video → GET /comments/{video_id}

b) Replies Management:

    Add a reply to a comment → POST /comments/{id}/replies
    Fetch replies for a comment → GET /comments/{id}/replies

c) Likes & Dislikes:

    Like a comment → POST /comments/{id}/reactions/like
    Dislike a comment → POST /comments/{id}/reactions/dislike
    Fetch reaction counts → GET /comments/{id}/reactions

d) Ranking & Sorting:

    Fetch comments for a video (Latest comments)→ GET /comments/{video_id}
    Fetch top comments (Top comments) → GET /comments/{video_id}/top-comments

## 2) Data Model:

Tables:

users

    id (Primary Key)
    name
    email
    created_at

videos

    id (Primary Key)
    title
    user_id (Foreign Key)
    created_at

comments

    id (Primary Key)
    video_id (Foreign Key)
    user_id (Foreign Key)
    parent_comment_id (nullable, for replies)
    created_at
    is_edited
    reply_to (Reply to any specific user)

reactions

    id (Primary Key)
    comment_id (Foreign Key)
    user_id (Foreign Key)
    type (2 reply, 1 like, -1 dislike)
    created_at

## 3) Simple and Efficient Ranking Algorithm:

Let’s rank "top comments" based on engagement and freshness:

    Top comments are the most engaged and relevant comments, often prioritized based on novelty, and user interaction. Also, consider new comments that quickly attract likes and replies keep discussions fresh and timely.

Algorithm to Calculate Final Comment Score:

1. Calculate **mostEngagedScore**:
   a. Count the total number of likes (`likes`).
   b. Count the total number of dislikes (`dislikes`).
   c. Count the total number of replies (`replies`).
   d. Compute the score:
      ```
      mostEngagedScore = (likes - dislikes) + replies
      ```

2. Calculate **noveltyScore**:
   a. Determine the number of days since the comment was created (`ageInDays`).
   b. Compute the novelty score:
      ```
      noveltyScore = 365 - ageInDays
      ```

3. Calculate **trendingScore**:
   a. Calculate the total number of responses:
      ```
      totalResponses = likes + dislikes + replies
      ```
   b. Ensure `ageInDays` is never zero to avoid division by zero.
   c. Compute the trending score:
      ```
      trendingScore = totalResponses / NULLIF(ageInDays, 0)
      ```

4. Calculate **finalScore**:
   ```
   finalScore = mostEngagedScore + noveltyScore + trendingScore
   ```

5. Sort comments in descending order of `finalScore`.

6. Output the sorted list of comments with their respective scores.

## 4) Added test cases for all routes

    ```
    php .\artisan test
    ```


  PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\CommentControllerTest
  ✓ it stores a new comment                                                                                0.40s  
  ✓ it updates a comment                                                                                   0.03s  
  ✓ it deletes a comment                                                                                   0.02s  
  ✓ it lists comments for a video                                                                          0.05s  
  ✓ it fetches top comments for a video                                                                    0.03s  
  ✓ it returns error when video not found                                                                  0.03s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                          0.02s  

   PASS  Tests\Feature\ReactionControllerTest
  ✓ it stores a like reaction for a comment                                                                0.02s  
  ✓ it removes a like reaction if already exists                                                           0.03s  
  ✓ it returns error when comment not found                                                                0.02s  
  ✓ it gets reactions for a comment                                                                        0.02s  

   PASS  Tests\Feature\ReplyControllerTest
  ✓ it stores a reply for a comment                                                                        0.02s  
  ✓ it returns error when comment not found                                                                0.01s  
  ✓ it gets replies for a comment                                                                          0.02s  

  Tests:    15 passed (38 assertions)
  Duration: 0.87s


## 5) REST api client's export

- I am using `insomnia` you can reach at https://insomnia.rest/download .
- Added the EXPORT in the home directory of the project `./Insomnia_2025-03-02.json`
- The export can be used directly in the `insomnia` to run the api smoothly.


