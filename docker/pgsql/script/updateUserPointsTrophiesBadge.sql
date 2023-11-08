DROP PROCEDURE IF EXISTS updateUserPointsTrophiesBadge;

CREATE PROCEDURE updateUserPointsTrophiesBadge(inout postId integer, INOUT userId integer)
LANGUAGE plpgsql
AS $$
BEGIN

declare postExists boolean := false; 
if ((select count(*) from posts where posts.id=postId) > 0) then 
	postExists = true;
end if;

declare userExists boolean := false; 
if ((select count(*) from users where id=userId) > 0) then 
	userExists = true;
end if;

if (postExists = true AND userExists = true) then

declare categoryId integer := select top 1 category_id from posts where id=postId;
declare postType integer := select top 1 type from posts where id=postId;

declare trophyPoint integer :=  select top 1 point from rewards where type like '%trophy%'; 

-- create point for user in this category
if ((select count(*) from user_point_category where user_id=userId and category_id=categoryId) <= 0) then 
	insert into user_point_category 
		(user_id, category_id, current_point, total_point)
		values 
		(userId, categoryId, 0, 0);
end if;

declare currentPoint integer := select current_point from user_point_category where user_id=userId and category_id=categoryId;

declare dateDiff integer := 1;
if (postType == 'challenge') then 
    declare startDate datetime := select top 1 start_date from posts where id=postId;
    declare endDate datetime := select top 1 end_date from posts where id=postId;
    dateDiff = SELECT DATEDIFF(days, startDate, endDate);
end if;

declare levelPost text := select top 1 level from posts where id=postId;
declare levelPoint integer  := 10;
if (levelPost == 'medium') then
    levelPoint = 20;
end if;
if (levelPost == 'hard') then 
    levelPoint = 30;
end if;


declare nbPoint integer := currentPoint + (levelPoint * dateDiff);
if (nbPoint < trophyPoint) then
    update user_point_category
    SET total_point = current_point+nbPoint, current_point = nbPoint
    where user_id=userId and category_id=categoryId; 
end if;
if (nbPoint >= trophyPoint) then
declare newCurrentPoint integer := currentPoint;
while newCurrentPoint >= trophyPoint loop
   newCurrentPoint = newCurrentPoint - trophyPoint;
end loop;
    update user_point_category
    SET total_point = current_point+nbPoint, current_point = newCurrentPoint
    where user_id=userId and category_id=categoryId; end if;
end if;
end if;

END;
$$

CREATE TRIGGER updateUserPointsTrophiesBadge
    AFTER INSERT
    ON user_post_participation
    FOR EACH ROW EXECUTE PROCEDURE updateUserPointsTrophiesBadge(new.participant_id, new.post_id);