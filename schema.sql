-- Insert into Contacts table
INSERT INTO Contacts (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by)
VALUES ('Mr.', 'James', 'Smith', 'james.smith@example.com', '123-456-7890', 'Tech Co.', 'Lead', 1, 1);

-- Insert into Notes table
INSERT INTO Notes (contact_id, comment, created_by) 
VALUES (1, 'This is a note about James.', 1);


-- Insert into Users table 
INSERT INTO Users (firstname, lastname, password, email, role) 
VALUES ('John', 'Doe', 'password123', 'john.doe@example.com', 'Admin');

