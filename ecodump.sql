

UPDATE users
SET position = 'Grenoble', 
    image = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAkvJIshHbWUa2M5J4TZxSiHZ4hqNiqAASbYkY1bOR3g&s',
    biography = '🚴‍♂️👣 | Passionné de montagne, de vélo et fervent supporter du PSG. Toujours aux côtés de mon fidèle compagnon LuKa. 🐶⚽️ #MountainLife #PSGForever'
WHERE id = 1;

UPDATE users
SET position = 'Le sud', 
    image = 'https://image-uniservice.linternaute.com/image/450/2146026473/12070546.jpg',
    biography = '⚽💻 | Marseillaise dans l''âme, experte en graphisme et amoureuse des widgets Flutter. Le design, c''est ma passion. Allez l''OM! 🔵⚪ #GraphicDesign #OMFan'
WHERE id = 3;

UPDATE users
SET position = 'Versailles', 
    image = 'https://static1.s123-cdn-static-a.com/uploads/3086630/2000_5e4bcee616b92.jpg',
    biography = 'PO ecogest🌳👑 #VersaillesLife #RunningQueen'
WHERE id = 2;

UPDATE users
SET position = 'Rennes', 
    image = 'https://sportsourcingsolution.com/cdn/shop/products/2023.png',
    biography = '🏎️🐱 | Accro à la F1 et Max Verstappen. Mon chat Tigrou est mon co-pilote au quotidien. 🏁👣 #F1Fan #MaxVerstappen'
WHERE id = 4;


INSERT INTO user_point_category (user_id, category_id, current_point, total_point, created_at, updated_at)
VALUES
  (4, 9, 300, 800, NOW(), NOW()),
  (4, 6, 300, 800, NOW(), NOW()),
  (4, 4, 300, 800, NOW(), NOW()),
  (1, 3, 300, 800, NOW(), NOW()),
  (1, 2, 300, 800, NOW(), NOW()),
  (3, 7, 300, 800, NOW(), NOW());


INSERT INTO user_trophy (user_id, category_id, created_at, updated_at)
VALUES
  (4, 9, NOW(), NOW()),
  (4, 6, NOW(), NOW()),
  (4, 4, NOW(), NOW()),
  (1, 3, NOW(), NOW()),
  (1, 2, NOW(), NOW()),
  (3, 7, NOW(), NOW());

INSERT INTO posts (category_id, author_id, title, description, type, level, start_date, end_date, created_at, updated_at, image, position)
VALUES (9, 4, 'Mon coin de paradis écologique 🌿', 'J''ai décidé de créer un jardin écologique dans mon espace ! Des plantes indigènes, un habitat accueillant pour la faune locale, et une belle harmonie avec la nature. 🦋🌺 Partagez vos conseils pour préserver la biodiversité ! #JardinEcologique #Biodiversité #NatureLovers', 'action', 'medium', NULL, NULL, NOW(), NOW(), 'https://www.lesentreprisesdupaysage.fr/content/uploads/2019/12/jardin_ecologique1.jpg', 'Rennes');

INSERT INTO posts (category_id, author_id, title, description, type, level, start_date, end_date, created_at, updated_at, image, position)
VALUES (2, 1, 'Journée sans viande, délicieuse et écologique ! 🌱🍲', 'Aujourd''hui, j''ai relevé le défi de passer une journée entière sans consommer de viande. 😌💚 C''était non seulement une expérience culinaire intéressante, mais aussi une petite action pour réduire mon empreinte carbone. Quelles alternatives végétales aimez-vous le plus ? Partagez vos conseils ! 🥕🥗 ', 'action', 'medium', NULL, NULL, NOW(), NOW(), 'https://sf2.viepratique.fr/wp-content/uploads/sites/2/2014/06/gnocchis-pesto-avocat-491x410.jpg', 'Grenoble');

INSERT INTO posts (category_id, author_id, title, description, type, level, start_date, end_date, created_at, updated_at, image, position)
VALUES (6, 4, 'Atelier DIY Zéro Déchet 💚', 'j''ai participé à  un atelier DIY Zéro Déchet aujourd''hui ! De la création de produits ménagers aux articles réutilisables, chaque geste compte. 🌍💪 Partagez vos propres créations et inspirez la communauté ! #DIY #ZéroDéchet #EcoFriendly', 'action', 'easy', NULL, NULL, NOW(), NOW(), 'https://i0.wp.com/vertcerise.com/blog/wp-content/uploads/2021/06/lingette-zero-dechet-facile-a-coudre.jpg', 'Rennes');

INSERT INTO posts (category_id, author_id, title, description, type, level, start_date, end_date, created_at, updated_at, image, position)
VALUES (7,3 , 'Un pas de plus vers l''efficacité énergétique ! ⚡🌐', 'Aujourd''hui, j''ai décidé d''optimiser ma consommation énergétique à la maison. 💻🔌 J''ai éteint les appareils en veille et investi dans des solutions écoénergétiques. Quelles sont vos astuces pour rendre votre espace de vie plus éco-friendly sur le plan énergétique ? Partagez vos idées ! 💡🏡', 'action', 'easy', NULL, NULL, NOW(), NOW(), 'https://www.mediacritik.com/wp-content/uploads/2022/08/shutterstock_1290508213-696x468.jpg', 'Le sud');

INSERT INTO posts (category_id, author_id, title, description, type, level, start_date, end_date, created_at, updated_at, image, position)
VALUES (3,1 , 'J''ai dit adieu aux emplettes plastiques ! ♻️🛒', 'Fier de ma petite victoire contre les plastiques à usage unique aujourd''hui ! 🌎🙌 J''ai adopté des sacs réutilisables et éliminé les emballages superflus. Quels sont vos conseils pour réduire les déchets plastiques pendant les courses ? Partagez vos astuces !', 'action', 'easy', NULL, NULL, NOW(), NOW(), 'https://boutique.guydemarle.com/5402-thickbox_default/pochon-a-legumes.jpg', 'Grenoble');

INSERT INTO posts (category_id, author_id, title, description, type, level, start_date, end_date, created_at, updated_at, image, position)
VALUES (4, 4, 'Partage d''expérience éco-inspirant! 🌟', 'Assistée à des conférences stimulante sur l''éco-conception de services numériques cette semaine. 🖥️💡 La révolution verte dans le monde numérique est en marche, et je suis ravi de faire partie de cette transformation. Partagez vos pensées et vos idées sur un avenir numérique plus durable ! 🌐💚 #ÉcoConception #NumériqueResponsable #ConférenceÉcoFriendly', 'challenge', 'medium', '2024-01-01', '2024-01-05', NOW(), NOW(), 'https://insphere.fr/wp-content/uploads/2021/02/original_1208550151-e15735718113711.jpg', 'Rennes');

INSERT INTO user_post_participation (participant_id, post_id, is_completed) 
VALUES(1, 1, 't'),
      (3, 2, 't'),
      (4, 3, 't'),
      (2, 4, 't'),
      (1, 5, 't'),
      (4, 6, 't'),
      (1, 7, 't'),
      (4, 8, 't'),
      (3, 9, 't'),
      (1, 10, 't'),
      (4, 11, 't');
