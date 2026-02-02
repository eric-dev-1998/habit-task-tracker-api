# Habit & Task Tracker API

A full-stack portfolio project focused on building a clean, scalable REST API to support a future mobile application.

This project implements a Laravel-based backend with a MySQL database, designed to be consumed by a Flutter mobile client. The goal is to demonstrate solid backend architecture, RESTful design principles, and real-world data modeling for productivity applications.

## 🚀 Tech Stack

Backend: Laravel (PHP)

Database: MySQL

API: RESTful JSON API

Auth (planned): Laravel Sanctum

Client (planned): Flutter mobile app

## 🧠 Core Concepts & Design Decisions

Tasks act as their own log using timestamps (created_at, updated_at).

Habits use a dedicated habit_logs table to track daily completion.

Relational database design with foreign keys and cascade deletes.

API-first architecture, no server-rendered views.

Designed with mobile consumption and scalability in mind.

## 🗄️ Database Structure

users — application users

tasks — one-off or general tasks

habits — recurring habits

habit_logs — daily habit completion records

## 📡 API Features (In Progress)

CRUD operations for tasks and habits

Habit completion tracking per day

JSON responses following REST conventions

Authentication via Laravel Sanctum (planned)

## 🔮 Roadmap

Implement authentication (Sanctum)

Secure API endpoints per user

Add validation & API resources

Connect Flutter mobile client

Add analytics (habit streaks, completion stats)

## 🎯 Purpose

This project is part of a personal developer portfolio, showcasing:

Backend architecture skills

REST API design

Database modeling

Preparation for real mobile app integration
