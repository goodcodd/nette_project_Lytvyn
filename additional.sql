SELECT t1.group_id, t1.group_name
FROM `groups` t1
LEFT JOIN user_groups t2 ON t1.group_id = t2.group_id
LEFT JOIN users u on u.user_id = t2.user_id;
