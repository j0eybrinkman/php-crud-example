Notes from Libre.Chat #PHP server:

(1) you're using prepared statements, unlike that tutorial. excellent. good work

(2) don't use `global`. this is what function args are for: if your code _needs_ something, you must _give_ it - not make the function look for it on its own

 function create(MySqli $db) { $db->whatever(...); 

this also applies to the whole `include db.php` thing - don't do this. this magically creates a global var in the script. it leaves your code hard to understand and easy to break

instead, create the $db and pass it around where you need it. like  $db = new MySqli(...); create($db);  etc

 (3) do not use FILTER_SANITIZE_* consts. you need to _validate_ your input, not try to "fix" them.
 
 $id = filter_var($_POST["id"], FILTER_VALIDATE_INT, ["min_range" => 1]); if ($id === false) { throw new Exception("invalid ID"); 
 
 FILTER_SANITIZE_SPECIAL_CHARS accomplishes _nothing_, and in fact can ruin your data when you try to store/compare/etc. in the db.
 
 (4) don't SELECT *. always select the,cols,you,want, EVEN IF you want all the cols. this keeps things clear and understandable and harder to break
 
  also don't just select everything - e.g., when you're looking for ONE record, only select that record.
  
  $select_by_id = "select the,cols,you,want from demo_table where id=?
  
  (5) don't  echo "a bunch of html";  and especially not from inside a function that does something else
  
  Or, in case of MySQL, paginate with LIMIT and OFFSET
  
   the function should do one specific thing (e.g., select rows you want) and then other code (e.g., a template file) should display that info
   
    i'll also show you, your PHP code can be sorted into two categories: code which _performs work_ (processing input, controller logic, database access, error handling, etc.), and code which  _produces output_ (header(), echo, <?= $var ?>, plain ol' <html>, etc.). work goes FIRST. output goes LAST. https://gist.github.com/adrian-enspired/9ed2542a695e2ebe30aa
