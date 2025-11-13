create DATABASE gamechanger;

use gamechanger;


CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  f_name VARCHAR(100) NOT NULL,
  l_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'recruiter', 'student') DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE users ADD COLUMN profile_photo VARCHAR(255) DEFAULT NULL;

-- ===========================
-- 2️⃣ OPPORTUNITY TYPES
-- ===========================
CREATE TABLE opportunity_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type_name VARCHAR(50) NOT NULL -- e.g. Study, Job, Internship
);


INSERT INTO opportunity_types (type_name)
VALUES ('Study'), ('Job'), ('Internship');


-- ===========================
-- 3️⃣ OPPORTUNITIES TABLE
-- ===========================
CREATE TABLE opportunities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  country VARCHAR(100),
  course VARCHAR(100),
  institution VARCHAR(150),
  co_web TEXT NOT NULL,
  job_title VARCHAR(150),
  industry VARCHAR(100),
  sponsorship ENUM('Sponsored', 'Unsponsored', 'Partially Sponsored'),
  type_id INT, -- FK from opportunity_types
  image_url VARCHAR(255),
  opp_url text NOT NULL,
  posted_by INT, -- FK from users
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (type_id) REFERENCES opportunity_types(id) ON DELETE SET NULL,
  FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE SET NULL
);



CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  service_type ENUM(
    'University Applications',
    'Visa Applications',
    'University Choices Assistance',
    'Career Guidance',
    'Visa Guidance',
    'Consultation'
  ) NOT NULL,
  preferred_date DATE,
  preferred_platform ENUM('Phone Call', 'WhatsApp Call', 'Google Meet'),
  message TEXT,
  status ENUM('Pending', 'Confirmed', 'Completed', 'Cancelled') DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE appointments
ADD COLUMN preferred_datetime DATETIME NULL;



-- Create table to store testimonials
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100),
    country VARCHAR(100),
    message TEXT NOT NULL,
    image_url VARCHAR(255) DEFAULT 'Assets/images/illustrations/user.png',
    rating DECIMAL(2,1) CHECK (rating >= 0 AND rating <= 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    banner VARCHAR(255),
    blog_json JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
