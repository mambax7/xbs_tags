-- phpMyAdmin SQL Dump
-- version 2.6.0
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Mar 01, 2006
-- Server version: 4.0.21
-- PHP Version: 4.3.9
-- 
-- Table creation for XBS MetaTags Module
-- (c) 2006 A Kitson
-- 
-- 
-- Database: `xbstags`
-- 

-- ------------------------------------------------------------
-- TAGS page index
-- ------------------------------------------------------------

CREATE TABLE tags_index (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  mid smallint(5) NOT NULL,
  pid smallint(5) NULL DEFAULT 0,
  tags_fname VARCHAR(255) NOT NULL,
  tags_title VARCHAR(255) NULL,
  tags_desc TEXT NULL,
  tags_keyword TEXT NULL,
  tags_config ENUM('db','textorder','leastorder','mostorder','xoops') DEFAULT 'mostorder',
  tags_maxkeyword INT(4) DEFAULT 30,
  tags_minkeylen INT(4) DEFAULT 5,
  PRIMARY KEY(id),
  INDEX k_fname(tags_fname)
);

CREATE TABLE tags_track (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  pid smallint(5) NOT NULL,
  keywords TEXT NULL,
  PRIMARY KEY(id),
  INDEX k_tid(pid)
);

CREATE TABLE tags_list (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  typ ENUM('black','white','page') DEFAULT 'page',
  pid smallint(5) NOT NULL,
  keywords TEXT NULL,
  PRIMARY KEY(id),
  INDEX k_pid(pid)
);
