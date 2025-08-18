# 📬 mailmm-service — Temporary Email Platform  

A **fullstack service** for creating disposable email addresses and receiving temporary messages.  
Frontend built with **Vue 3, Pinia, and Tailwind CSS**, backend powered by **PHP Laravel, PostgreSQL, and WebSockets**, deployed on **Ubuntu/Linux with Docker**.  

---

## ✨ Features  
- 📧 Generate temporary/disposable email addresses  
- 📩 Receive and view emails in real time (WebSocket integration)  
- 🗑️ Delete inbox when no longer needed  
- 🎨 Responsive UI with Vue 3 + Tailwind  
- ⚡ Global state management with Pinia  
- 🎥 Smooth animations with Framer Motion  
- 🐘 Backend with Laravel + PostgreSQL  
- 🔌 Real-time delivery using WebSockets (Laravel Reverb)  
- 🐳 Dockerized deployment on Ubuntu  

---

## 🗂 Tech Stack  
**Frontend:**  
- Vue 3 (Composition API)  
- Pinia (state management)  
- TypeScript  
- Tailwind CSS  
- Framer Motion (animations)  
- Dockerized deployment  

**Backend:**  
- PHP Laravel  
- PostgreSQL  
- WebSockets (Laravel Reverb for optimization)  
- Dockerized services  
- Ubuntu/Linux hosting  

---

## 📁 Project Structure 
- /app # Laravel core application code
- /public # Public entry (Laravel + Vite build output)
- /resources # Vue core application code
- /routes # Laravel routes

---

## 🚀 Getting Started  

Clone the repo and start services with Docker:  
```bash
git clone https://github.com/s1wos/skorleep.git
docker compose up --build
```
Go to localhost:8080


---

📋 Roadmap
 - Disposable email generation
 - Real-time inbox updates with WebSockets
 - PostgreSQL integration
 - Dockerized deployment
 - Custom domain support
 - Email forwarding

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
