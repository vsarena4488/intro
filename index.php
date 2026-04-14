<?php
$formSuccess = false;
$formMessage = '';
$formData = [
    'name' => '',
    'email' => '',
    'subject' => '',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Based on contact.php + portfolio_db.sql (contact_messages table)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'portfolio_db');
    define('YOUR_EMAIL', 'vishalsarena44@gmail.com');

    function clean_input($value)
    {
        return htmlspecialchars(trim(strip_tags((string)$value)), ENT_QUOTES, 'UTF-8');
    }

    $formData['name'] = clean_input($_POST['name'] ?? '');
    $formData['email'] = clean_input($_POST['email'] ?? '');
    $formData['subject'] = clean_input($_POST['subject'] ?? '');
    $formData['message'] = clean_input($_POST['message'] ?? '');

    if (
        $formData['name'] === '' ||
        $formData['email'] === '' ||
        $formData['subject'] === '' ||
        $formData['message'] === ''
    ) {
        $formMessage = 'All fields are required.';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $formMessage = 'Please enter a valid email address.';
    } else {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $stmt = $pdo->prepare(
                'INSERT INTO contact_messages (name, email, subject, message, created_at)
                 VALUES (:name, :email, :subject, :message, NOW())'
            );
            $stmt->execute([
                ':name' => $formData['name'],
                ':email' => $formData['email'],
                ':subject' => $formData['subject'],
                ':message' => $formData['message'],
            ]);

            $headers = "From: Portfolio Contact <noreply@yourdomain.com>\r\n"
                . "Reply-To: {$formData['email']}\r\n"
                . "MIME-Version: 1.0\r\n"
                . "Content-Type: text/plain; charset=UTF-8\r\n";

            $mailBody = "New portfolio message\n\n"
                . "Name: {$formData['name']}\n"
                . "Email: {$formData['email']}\n"
                . "Subject: {$formData['subject']}\n\n"
                . "Message:\n{$formData['message']}\n";

            @mail(YOUR_EMAIL, 'Portfolio: ' . $formData['subject'], $mailBody, $headers);

            $formSuccess = true;
            $formMessage = "Message sent successfully. I'll get back to you soon.";
            $formData = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
        } catch (PDOException $e) {
            error_log('DB Error: ' . $e->getMessage());
            $formMessage = 'Database error. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vishalkumar Sarena | Full-Stack Developer</title>
    <meta
      name="description"
      content="Vishalkumar Sarena – Computer Engineer & Full-Stack Developer from Rajkot. Portfolio showcasing projects, skills, and experience."
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Fira+Code:wght@300;400;500&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="index.css">

  </head>
  <body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#hero"
          ><span>&lt;</span>VS<span>/&gt;</span></a
        >
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#nm"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="nm">
          <ul class="navbar-nav gap-1">
            <li class="nav-item">
              <a class="nav-link" href="#about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#skills">Skills</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#experience">Experience</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#projects">Projects</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#education">Education</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#contact">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- HERO -->
    <section id="hero">
      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-6">
            <div class="hero-tag">&#x1F389; Available for Opportunities</div>
            <h1 class="hero-name">
              Vishal<br /><span class="highlight">Kumar Sarena</span>
            </h1>
            <p class="hero-title">
              <span id="tw"></span><span class="cursor"></span>
            </p>
            <p class="hero-desc">
              Dedicated Computer Engineering undergraduate and aspiring
              Full-Stack Developer from Rajkot, building scalable web
              applications with clean code and intuitive interfaces.
            </p>
            <div class="d-flex flex-wrap gap-3 mt-4">
              <a href="#projects" class="btn-pc">View Projects</a>
              <a href="#contact" class="btn-oc">Get in Touch</a>
            </div>
            <div class="hero-social mt-4">
              <a
                href="https://www.linkedin.com/in/vishal-sarena-139408322/"
                target="_blank"
                ><i class="bi bi-linkedin"></i
              ></a>
              <a href="mailto:vishalsarena44@gmail.com"
                ><i class="bi bi-envelope"></i
              ></a>
              <a href="tel:9316830538"><i class="bi bi-telephone"></i></a>
              <a href="https://github.com/vsarena4488" target="_blank"><i class="bi bi-github"></i></a>
            </div>
          </div>
          <div class="col-lg-6 d-flex justify-content-center">
            <div class="hero-code-block">
              <span class="cc">// developer.profile</span><br />
              {<br />
              &nbsp;&nbsp;<span class="ck">"name"</span>:
              <span class="cs">"Vishalkumar Sarena"</span>,<br />
              &nbsp;&nbsp;<span class="ck">"role"</span>:
              <span class="cs">"Full-Stack Developer"</span>,<br />
              &nbsp;&nbsp;<span class="ck">"location"</span>:
              <span class="cs">"Rajkot, Gujarat"</span>,<br />
              &nbsp;&nbsp;<span class="ck">"university"</span>:
              <span class="cs">"RK University"</span>,<br />
              &nbsp;&nbsp;<span class="ck">"stack"</span>: [<br />
              &nbsp;&nbsp;&nbsp;&nbsp;<span class="cs">"HTML"</span>,
              <span class="cs">"CSS"</span>, <span class="cs">"JS"</span>,<br />
              &nbsp;&nbsp;&nbsp;&nbsp;<span class="cs">"PHP"</span>,
              <span class="cs">"MySQL"</span>,
              <span class="cs">"Bootstrap"</span><br />
              &nbsp;&nbsp;],<br />
              &nbsp;&nbsp;<span class="ck">"openToWork"</span>:
              <span class="cv">true</span><br />
              }
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ABOUT -->
    <section id="about">
      <div class="container">
        <div class="reveal">
          <span class="section-tag">01. about_me</span>
          <h2 class="section-title">Who I Am</h2>
          <div class="section-line"></div>
        </div>
        <div class="row g-4 align-items-start">
          <div class="col-lg-7 reveal">
            <div class="card-dark">
              <p
                style="
                  color: var(--text-muted);
                  line-height: 1.9;
                  margin-bottom: 1.2rem;
                "
              >
                I'm a dedicated Computer Engineering undergraduate and aspiring
                Full-Stack Developer with a solid foundation in programming,
                databases, and modern web technologies. Skilled in building
                academic and personal projects that demonstrate strong
                problem-solving and clean coding practices.
              </p>
              <p
                style="
                  color: var(--text-muted);
                  line-height: 1.9;
                  margin-bottom: 1.2rem;
                "
              >
                Focused on creating intuitive user interfaces and reliable
                backend functionality while continuously learning new
                technologies. Passionate about developing scalable web
                applications and committed to growing through hands-on practice,
                project work, and real-world challenges.
              </p>
              <p style="color: var(--text-muted); line-height: 1.9">
                Currently pursuing B.Tech in Computer Engineering at RK
                University, Rajkot — actively building projects that bridge
                theory and real-world application.
              </p>
              <div class="mt-3">
                <p
                  style="
                    font-size: 0.82rem;
                    color: var(--text-muted);
                    margin-bottom: 0.5rem;
                  "
                >
                  Languages Spoken
                </p>
                <span class="stag"> English </span>
                <span class="stag"> Hindi </span>
                <span class="stag"> Gujarati </span>
              </div>
            </div>
          </div>
          <div class="col-lg-5 reveal">
            <div class="row g-3">
              <div class="col-6">
                <div class="stat-box">
                  <div class="stat-num">4+</div>
                  <div class="stat-lbl">Projects Built</div>
                </div>
              </div>
              <div class="col-6">
                <div class="stat-box">
                  <div class="stat-num">2</div>
                  <div class="stat-lbl">Certifications</div>
                </div>
              </div>
              <div class="col-6">
                <div class="stat-box">
                  <div class="stat-num">1</div>
                  <div class="stat-lbl">Internship</div>
                </div>
              </div>
              <div class="col-6">
                <div class="stat-box">
                  <div class="stat-num">3</div>
                  <div class="stat-lbl">Languages Spoken</div>
                </div>
              </div>
            </div>
            <div class="card-dark mt-3" style="padding: 1.2rem">
              <p
                style="
                  font-size: 0.82rem;
                  color: var(--text-muted);
                  margin-bottom: 0.6rem;
                "
              >
                Hobbies & Interests
              </p>
              <span class="stag">&#x1F3CF; Cricket</span>
              <span class="stag">&#x1F3F8; Badminton</span>
              <span class="stag">&#x1F3CA; Swimming</span>
              <span class="stag">&#x2708;&#xFE0F; Travelling</span>
              <span class="stag">&#x1F3B5; Music</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SKILLS -->
    <section id="skills" style="background: rgba(255, 255, 255, 0.01)">
      <div class="container">
        <div class="reveal">
          <span class="section-tag">02. skills</span>
          <h2 class="section-title">Tech Stack</h2>
          <div class="section-line"></div>
        </div>
        <div class="row g-4">
          <div class="col-md-6 col-lg-3 reveal">
            <div class="skill-cat">
              <h5><i class="bi bi-code-slash me-2"></i>Frontend</h5>
              <span class="stag">HTML5</span><span class="stag">CSS3</span
              ><span class="stag">JavaScript</span
              ><span class="stag">Bootstrap 5</span
              ><span class="stag">Responsive Design</span>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 reveal">
            <div class="skill-cat">
              <h5><i class="bi bi-server me-2"></i>Backend</h5>
              <span class="stag">PHP</span><span class="stag">MySQL</span
              ><span class="stag">REST APIs</span
              ><span class="stag">CRUD Ops</span><span class="stag">OOP</span>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 reveal">
            <div class="skill-cat">
              <h5><i class="bi bi-tools me-2"></i>Tools</h5>
              <span class="stag">Git & GitHub</span
              ><span class="stag">Figma</span><span class="stag">VS Code</span
              ><span class="stag">IntelliJ IDEA</span
              ><span class="stag">Chrome DevTools</span>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 reveal">
            <div class="skill-cat">
              <h5><i class="bi bi-lightbulb me-2"></i>Concepts</h5>
              <span class="stag">DSA</span
              ><span class="stag">Full-Stack Dev</span
              ><span class="stag">Database Design</span
              ><span class="stag">Problem Solving</span
              ><span class="stag">UI/UX</span>
            </div>
          </div>
        </div>
        <div class="row g-3 mt-4 reveal">
          <div class="col-12">
            <p style="font-size: 0.82rem; color: var(--text-muted)">
              // Core Strengths
            </p>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-box">
              <div style="font-size: 1.5rem; margin-bottom: 0.4rem">
                &#x1F4AC;
              </div>
              <div class="stat-lbl">Communication & Teamwork</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-box">
              <div style="font-size: 1.5rem; margin-bottom: 0.4rem">
                &#x1F9F9;
              </div>
              <div class="stat-lbl">Clean Code Commitment</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-box">
              <div style="font-size: 1.5rem; margin-bottom: 0.4rem">
                &#x1F9E9;
              </div>
              <div class="stat-lbl">Problem Solving</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-box">
              <div style="font-size: 1.5rem; margin-bottom: 0.4rem">
                &#x23F0;
              </div>
              <div class="stat-lbl">Time Management</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- EXPERIENCE -->
    <section id="experience">
      <div class="container">
        <div class="reveal">
          <span class="section-tag">03. experience</span>
          <h2 class="section-title">Work Experience</h2>
          <div class="section-line"></div>
        </div>
        <div class="row">
          <div class="col-lg-8 reveal">
            <div class="timeline">
              <div class="tl-item">
                <div class="tl-card">
                  <span class="period">Mar 2026 – Apr 2026</span>
                  <h4 class="t-role">Front End Development Intern</h4>
                  <p class="t-company">
                    <i class="bi bi-building me-1"></i>Sysslan IT Solutions,
                    Rajkot
                  </p>
                  <p
                    style="
                      color: var(--text-muted);
                      font-size: 0.88rem;
                      line-height: 1.8;
                    "
                  >
                    Created a front-end ticket booking website using HTML, CSS,
                    and JavaScript. Designed a clean and responsive UI where
                    users can browse events and start the booking process. Built
                    reusable event cards, added basic interactivity, and ensured
                    the site works smoothly on all devices.
                  </p>
                  <div class="proj-tech mt-3">
                    <span class="tech-badge">HTML</span
                    ><span class="tech-badge">CSS</span
                    ><span class="tech-badge">JavaScript</span
                    ><span class="tech-badge">Responsive UI</span>
                  </div>
                  <a
                    href="https://www.linkedin.com/posts/vishal-sarena-139408322_sysslan-it-solutions-activity-7447541747758440448-tIiH?utm_source=share&utm_medium=member_desktop&rcm=ACoAAFGTBr0Bt1HBfzgJQDc4brp9pIEohxNMUv4"
                    class="btn-pc exp-cert-btn mt-3"
                    target="_blank"
                    rel="noopener noreferrer"
                    >View Certificate</a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PROJECTS -->
    <section id="projects" style="background: rgba(255, 255, 255, 0.01)">
      <div class="container">
        <div class="reveal">
          <span class="section-tag">04. projects</span>
          <h2 class="section-title">Featured Projects</h2>
          <div class="section-line"></div>
        </div>
        <div class="row g-4">
          <div class="col-md-6 reveal">
            <div class="proj-card">
              <div class="proj-num">// project_01</div>
              <div class="proj-icon"><i class="bi bi-trophy-fill"></i></div>
              <h4>Box Cricket Full-Stack Management</h4>
              <p>
                A complete full-stack web application demonstrating API
                development, database connectivity, server-side logic, and
                responsive UI design. Features full CRUD operations and user
                data handling.
              </p>
              <div class="proj-tech">
                <span class="tech-badge">PHP</span
                ><span class="tech-badge">MySQL</span
                ><span class="tech-badge">HTML/CSS</span
                ><span class="tech-badge">JavaScript</span
                ><span class="tech-badge">REST API</span>
              </div>
              <a
                href="https://github.com/vsarena4488"
                class="btn-pc exp-cert-btn mt-3"
                target="_blank"
                rel="noopener noreferrer"
                >View on GitHub</a
              >
            </div>
          </div>
          <div class="col-md-6 reveal">
            <div class="proj-card">
              <div class="proj-num">// project_02</div>
              <div class="proj-icon"><i class="bi bi-person-badge"></i></div>
              <h4>Personal Portfolio Website</h4>
              <p>
                Designed and developed a fully responsive personal portfolio
                website showcasing skills, education, projects, and
                achievements. Demonstrates modern UI design principles and
                responsive layout techniques.
              </p>
              <div class="proj-tech">
                <span class="tech-badge">HTML5</span
                ><span class="tech-badge">CSS3</span
                ><span class="tech-badge">Bootstrap</span
                ><span class="tech-badge">JavaScript</span>
              </div>
              <a
                href="https://github.com/vsarena4488"
                class="btn-pc exp-cert-btn mt-3"
                target="_blank"
                rel="noopener noreferrer"
                >View on GitHub</a
              >
            </div>
          </div>
          <div class="col-md-6 reveal">
            <div class="proj-card">
              <div class="proj-num">// project_03</div>
              <div class="proj-icon"><i class="bi bi-shield-lock"></i></div>
              <h4>Authentication UI System</h4>
              <p>
                Built multiple login and registration web pages strengthening
                understanding of form structure and UI patterns. Improved
                ability to design intuitive, reusable UI components with good
                UX.
              </p>
              <div class="proj-tech">
                <span class="tech-badge">HTML</span
                ><span class="tech-badge">CSS</span
                ><span class="tech-badge">JavaScript</span
                ><span class="tech-badge">Form Design</span>
              </div>
              <a
                href="https://github.com/vsarena4488"
                class="btn-pc exp-cert-btn mt-3"
                target="_blank"
                rel="noopener noreferrer"
                >View on GitHub</a
              >
            </div>
          </div>
          <div class="col-md-6 reveal">
            <div class="proj-card">
              <div class="proj-num">// project_04</div>
              <div class="proj-icon"><i class="bi bi-controller"></i></div>
              <h4>Rock–Paper–Scissors Game</h4>
              <p>
                An interactive web-based game built to enhance JavaScript
                logic-building and DOM manipulation skills. Demonstrates
                capability to create engaging client-side applications with
                effective game logic.
              </p>
              <div class="proj-tech">
                <span class="tech-badge">HTML</span
                ><span class="tech-badge">CSS</span
                ><span class="tech-badge">JavaScript</span
                ><span class="tech-badge">DOM API</span>
              </div>
              <a
                href="https://github.com/vsarena4488"
                class="btn-pc exp-cert-btn mt-3"
                target="_blank"
                rel="noopener noreferrer"
                >View on GitHub</a
              >
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- EDUCATION & CERTS -->
    <section id="education">
      <div class="container">
        <div class="reveal">
          <span class="section-tag">05. education</span>
          <h2 class="section-title">Academic Journey</h2>
          <div class="section-line"></div>
        </div>
        <div class="row g-4 mb-5">
          <div class="col-lg-4 reveal">
            <div class="edu-card">
              <span class="period">2024 – Present</span>
              <h5 style="font-weight: 700; margin: 0.6rem 0 0.3rem">
                B.Tech – Computer Engineering
              </h5>
              <p
                style="
                  color: var(--accent);
                  font-size: 0.85rem;
                  margin-bottom: 0.4rem;
                "
              >
                RK University, Rajkot
              </p>
              <p style="color: var(--text-muted); font-size: 0.8rem">
                Tramba, Gujarat
              </p>
            </div>
          </div>
          <div class="col-lg-4 reveal">
            <div class="edu-card">
              <span class="period">2023 – 2024</span>
              <h5 style="font-weight: 700; margin: 0.6rem 0 0.3rem">
                Higher Secondary (Science)
              </h5>
              <p
                style="
                  color: var(--accent);
                  font-size: 0.85rem;
                  margin-bottom: 0.4rem;
                "
              >
                Royal Science School Boys Unit
              </p>
              <p style="color: var(--text-muted); font-size: 0.8rem">
                NH 27, Dhoraji, Gujarat
              </p>
            </div>
          </div>
          <div class="col-lg-4 reveal">
            <div class="edu-card">
              <span class="period">2021 – 2022</span>
              <h5 style="font-weight: 700; margin: 0.6rem 0 0.3rem">
                Secondary School
              </h5>
              <p
                style="
                  color: var(--accent);
                  font-size: 0.85rem;
                  margin-bottom: 0.4rem;
                "
              >
                Royal International School
              </p>
              <p style="color: var(--text-muted); font-size: 0.8rem">
                Dhoraji, Gujarat
              </p>
            </div>
          </div>
        </div>

        <div class="reveal">
          <span class="section-tag">06. certifications &amp; achievements</span>
          <h2 class="section-title">Certifications</h2>
          <div class="section-line"></div>
        </div>
        <div class="row g-3">
          <div class="col-md-6 col-lg-4 reveal">
            <div class="cert-card">
              <div class="cert-icon"><i class="bi bi-award"></i></div>
              <div>
                <h6>NPTEL – The Joy of Computing Using Python</h6>
                <p>
                  Government-certified course on Python programming, logic
                  building, and problem-solving.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 reveal">
            <div class="cert-card">
              <div class="cert-icon"><i class="bi bi-pen"></i></div>
              <div>
                <h6>Figma – Web & Mobile Projects from Scratch</h6>
                <p>
                  UI/UX fundamentals, wireframing, prototyping, and responsive
                  interface design.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 reveal">
            <div class="cert-card">
              <div class="cert-icon"><i class="bi bi-code-square"></i></div>
              <div>
                <h6>Web Development Workshop</h6>
                <p>
                  Hands-on frontend experience with HTML, CSS, and JavaScript
                  through live sessions.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 reveal">
            <div class="cert-card">
              <div class="cert-icon"><i class="bi bi-cpu"></i></div>
              <div>
                <h6>IoT-Based Academic Projects</h6>
                <p>
                  Developed IoT-based projects as part of academic and personal
                  exploration.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" style="background: rgba(255, 255, 255, 0.01)">
      <div class="container">
        <div class="reveal">
          <span class="section-tag">07. contact</span>
          <h2 class="section-title">Get In Touch</h2>
          <div class="section-line"></div>
        </div>
        <div class="row g-4">
          <div class="col-lg-5 reveal">
            <div class="card-dark">
              <h5 style="margin-bottom: 1.5rem; font-weight: 600">
                Let's connect
              </h5>
              <a href="mailto:vishalsarena44@gmail.com" class="contact-item">
                <div class="ci"><i class="bi bi-envelope"></i></div>
                <div>
                  <p
                    style="
                      font-size: 0.75rem;
                      color: var(--text-muted);
                      margin: 0;
                    "
                  >
                    Email
                  </p>
                  <p style="font-size: 0.9rem; margin: 0">
                    vishalsarena44@gmail.com
                  </p>
                </div>
              </a>
              <a href="tel:9316830538" class="contact-item">
                <div class="ci"><i class="bi bi-telephone"></i></div>
                <div>
                  <p
                    style="
                      font-size: 0.75rem;
                      color: var(--text-muted);
                      margin: 0;
                    "
                  >
                    Phone
                  </p>
                  <p style="font-size: 0.9rem; margin: 0">+91 9316830538</p>
                </div>
              </a>
              <a
                href="https://www.linkedin.com/in/vishal-sarena-139408322/"
                target="_blank"
                class="contact-item"
              >
                <div class="ci"><i class="bi bi-linkedin"></i></div>
                <div>
                  <p
                    style="
                      font-size: 0.75rem;
                      color: var(--text-muted);
                      margin: 0;
                    "
                  >
                    LinkedIn
                  </p>
                  <p style="font-size: 0.9rem; margin: 0">
                    vishal-sarena-139408322
                  </p>
                </div>
              </a>
              <div class="contact-item">
                <div class="ci"><i class="bi bi-geo-alt"></i></div>
                <div>
                  <p
                    style="
                      font-size: 0.75rem;
                      color: var(--text-muted);
                      margin: 0;
                    "
                  >
                    Location
                  </p>
                  <p style="font-size: 0.9rem; margin: 0">
                    Rajkot, Gujarat, India
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-7 reveal">
            <div class="card-dark">
              <h5 style="margin-bottom: 1.5rem; font-weight: 600">
                Send a message
              </h5>
              <?php if ($formMessage !== ''): ?>
              <div class="msg-ok <?php echo $formSuccess ? '' : 'msg-err'; ?>">
                <?php echo $formSuccess ? '&#x2705; ' : '&#x274C; '; ?><?php echo $formMessage; ?>
              </div>
              <?php endif; ?>
              <form id="cf" action="index.php#contact" method="POST">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label>Your Name</label>
                    <input
                      type="text"
                      name="name"
                      class="fc"
                      placeholder="John Doe"
                      value="<?php echo $formData['name']; ?>"
                      required
                    />
                  </div>
                  <div class="col-md-6">
                    <label>Email Address</label>
                    <input
                      type="email"
                      name="email"
                      class="fc"
                      placeholder="john@example.com"
                      value="<?php echo $formData['email']; ?>"
                      required
                    />
                  </div>
                  <div class="col-12">
                    <label>Subject</label>
                    <input
                      type="text"
                      name="subject"
                      class="fc"
                      placeholder="Internship / Project Collaboration"
                      value="<?php echo $formData['subject']; ?>"
                      required
                    />
                  </div>
                  <div class="col-12">
                    <label>Message</label>
                    <textarea
                      name="message"
                      class="fc"
                      rows="5"
                      placeholder="Tell me about your opportunity..."
                      required
                      style="resize: vertical"
                    ><?php echo $formData['message']; ?></textarea>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn-send">
                      <i class="bi bi-send me-2"></i>Send Message
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer>
      <div class="container">
        <p>
          Designed &amp; Built by <span>Vishalkumar Sarena</span> &copy; 2026
        </p>
        <p style="margin-top: 0.4rem; font-size: 0.75rem">
          Computer Engineer &middot; Full-Stack Developer &middot; Rajkot,
          Gujarat
        </p>
      </div>
    </footer>

    <a href="#hero" id="btt"><i class="bi bi-arrow-up"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Typewriter
      const phrases = [
        "Full-Stack Developer",
        "Computer Engineer",
        "UI/UX Enthusiast",
        "Problem Solver",
        "Open to Opportunities",
      ];
      let pi = 0,
        ci = 0,
        del = false;
      const tw = document.getElementById("tw");
      function type() {
        const cur = phrases[pi];
        tw.textContent = del ? cur.slice(0, --ci) : cur.slice(0, ++ci);
        if (!del && ci === cur.length) {
          setTimeout(() => (del = true), 1800);
        } else if (del && ci === 0) {
          del = false;
          pi = (pi + 1) % phrases.length;
        }
        setTimeout(type, del ? 55 : 90);
      }
      type();

      // Scroll reveal
      const obs = new IntersectionObserver(
        (e) => {
          e.forEach((x) => {
            if (x.isIntersecting) x.target.classList.add("visible");
          });
        },
        { threshold: 0.1 },
      );
      document.querySelectorAll(".reveal").forEach((el) => obs.observe(el));

      // Back to top
      const btt = document.getElementById("btt");
      window.addEventListener("scroll", () =>
        btt.classList.toggle("show", scrollY > 400),
      );

      // Active nav
      const secs = document.querySelectorAll("section[id]");
      window.addEventListener("scroll", () => {
        let cur = "";
        secs.forEach((s) => {
          if (scrollY >= s.offsetTop - 100) cur = s.id;
        });
        document.querySelectorAll(".nav-link").forEach((l) => {
          l.style.color =
            l.getAttribute("href") === "#" + cur ? "var(--primary)" : "";
        });
      });

    </script>
  </body>
</html>
