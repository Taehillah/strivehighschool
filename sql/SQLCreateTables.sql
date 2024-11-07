-- Create Administrators Table
CREATE TABLE Administrators (
    Admin_ID INT IDENTITY(1,1) PRIMARY KEY,
    email NVARCHAR(255) NOT NULL UNIQUE,
    password NVARCHAR(255) NOT NULL
);

-- Create Users Table
CREATE TABLE Users (
    User_ID INT IDENTITY(1,1) PRIMARY KEY,
    role NVARCHAR(50) CHECK (role IN ('Learner', 'Parent', 'Guardian')) NOT NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    password NVARCHAR(255) NOT NULL,
    route NVARCHAR(255) NOT NULL,
    full_name NVARCHAR(255) NOT NULL,
    grade INT NOT NULL
);

-- Create Buses Table
CREATE TABLE Buses (
    Bus_ID INT IDENTITY(1,1) PRIMARY KEY,
    Bus_Registration NVARCHAR(255) NOT NULL UNIQUE,
    Bus_Route NVARCHAR(255) NOT NULL,
    Bus_Timings TIME NOT NULL,
    capacity INT NOT NULL
);

-- Create Activities Table
CREATE TABLE Activities (
    Activity_ID INT IDENTITY(1,1) PRIMARY KEY,
    Learner_ID INT NOT NULL,
    Learner_Name NVARCHAR(255) NOT NULL,
    Route_Traveled NVARCHAR(255) NOT NULL,
    Bus_Taken NVARCHAR(255) NOT NULL,
    activity_date DATE NOT NULL,
    FOREIGN KEY (Learner_ID) REFERENCES Users(User_ID)
);
