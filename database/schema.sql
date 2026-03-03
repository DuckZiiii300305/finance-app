-- =====================================
-- CREATE DATABASE
-- =====================================
CREATE DATABASE IF NOT EXISTS finance_app
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE finance_app;

-- =====================================
-- TABLE: users
-- =====================================
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================
-- TABLE: saving_goals
-- =====================================
CREATE TABLE saving_goals (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    name VARCHAR(255) NOT NULL,
    target_amount DECIMAL(15,2) NOT NULL,
    current_amount DECIMAL(15,2) DEFAULT 0,
    deadline DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_goal_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================
-- TABLE: incomes
-- =====================================
CREATE TABLE incomes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) DEFAULT NULL,
    amount DECIMAL(15,2) DEFAULT NULL,
    source VARCHAR(150) DEFAULT NULL,
    note TEXT DEFAULT NULL,
    income_date DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_income_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================
-- TABLE: expenses
-- (Đúng cấu trúc bạn gửi)
-- =====================================
CREATE TABLE expenses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) DEFAULT NULL,
    amount DECIMAL(15,2) DEFAULT NULL,
    category VARCHAR(100) DEFAULT NULL,
    note TEXT DEFAULT NULL,
    expense_date DATE DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    goal_id INT(11) DEFAULT NULL,

    CONSTRAINT fk_expense_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_expense_goal
        FOREIGN KEY (goal_id) REFERENCES saving_goals(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================
-- TABLE: saving_transactions
-- (Lưu lịch sử nạp tiền vào goal)
-- =====================================
CREATE TABLE saving_transactions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    goal_id INT(11) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    note TEXT DEFAULT NULL,

    CONSTRAINT fk_transaction_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_transaction_goal
        FOREIGN KEY (goal_id) REFERENCES saving_goals(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================
-- INDEXES (tối ưu query)
-- =====================================
CREATE INDEX idx_expense_user ON expenses(user_id);
CREATE INDEX idx_income_user ON incomes(user_id);
CREATE INDEX idx_goal_user ON saving_goals(user_id);
CREATE INDEX idx_transaction_goal ON saving_transactions(goal_id);-- =====================================
-- CREATE DATABASE
-- =====================================
CREATE DATABASE IF NOT EXISTS finance_app
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE finance_app;

-- =====================================
-- TABLE: users
-- =====================================
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================
-- TABLE: saving_goals
-- =====================================
CREATE TABLE saving_goals (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    name VARCHAR(255) NOT NULL,
    target_amount DECIMAL(15,2) NOT NULL,
    current_amount DECIMAL(15,2) DEFAULT 0,
    deadline DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_goal_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================
-- TABLE: incomes
-- =====================================
CREATE TABLE incomes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) DEFAULT NULL,
    amount DECIMAL(15,2) DEFAULT NULL,
    source VARCHAR(150) DEFAULT NULL,
    note TEXT DEFAULT NULL,
    income_date DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_income_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================
-- TABLE: expenses
-- (Đúng cấu trúc bạn gửi)
-- =====================================
CREATE TABLE expenses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) DEFAULT NULL,
    amount DECIMAL(15,2) DEFAULT NULL,
    category VARCHAR(100) DEFAULT NULL,
    note TEXT DEFAULT NULL,
    expense_date DATE DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    goal_id INT(11) DEFAULT NULL,

    CONSTRAINT fk_expense_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_expense_goal
        FOREIGN KEY (goal_id) REFERENCES saving_goals(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================
-- TABLE: saving_transactions
-- (Lưu lịch sử nạp tiền vào goal)
-- =====================================
CREATE TABLE saving_transactions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    goal_id INT(11) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    note TEXT DEFAULT NULL,

    CONSTRAINT fk_transaction_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_transaction_goal
        FOREIGN KEY (goal_id) REFERENCES saving_goals(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================
-- INDEXES (tối ưu query)
-- =====================================
CREATE INDEX idx_expense_user ON expenses(user_id);
CREATE INDEX idx_income_user ON incomes(user_id);
CREATE INDEX idx_goal_user ON saving_goals(user_id);
CREATE INDEX idx_transaction_goal ON saving_transactions(goal_id);