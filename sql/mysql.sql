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

CREATE TABLE xbstags_index (
    id              INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    mid             SMALLINT(5)      NOT NULL,
    pid             SMALLINT(5)      NULL                                    DEFAULT 0,
    tags_fname      VARCHAR(255)     NOT NULL,
    tags_title      VARCHAR(255)     NULL,
    tags_desc       TEXT             NULL,
    tags_keyword    TEXT             NULL,
    tags_config     ENUM ('db','textorder','leastorder','mostorder','xoops') DEFAULT 'mostorder',
    tags_maxkeyword INT(4)                                                   DEFAULT 30,
    tags_minkeylen  INT(4)                                                   DEFAULT 5,
    PRIMARY KEY (id),
    INDEX k_fname (tags_fname)
)
    ENGINE = MyISAM;

CREATE TABLE xbstags_track (
    id             INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    pid            SMALLINT(5)      NOT NULL,
    track_keywords TEXT             NULL,
    PRIMARY KEY (id),
    INDEX k_tid (pid)
)
    ENGINE = MyISAM;

CREATE TABLE xbstags_list (
    id            INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    typ           ENUM ('black','white','page') DEFAULT 'page',
    pid           SMALLINT(5)      NOT NULL,
    list_keywords TEXT             NULL,
    PRIMARY KEY (id),
    INDEX k_pid (pid)
)
    ENGINE = MyISAM;
