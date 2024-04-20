CREATE TABLE IF NOT EXISTS `cbcs_subject_offered_same_branch_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_offered_id` int(11) NOT NULL,
  `same_branch_opt_status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;