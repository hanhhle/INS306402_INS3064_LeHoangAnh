
# Part 1: Normalization
## Task 1: 

| Table Name  | Primary Key           | Foreign Key         | Normal Form | Description                                     |
| :---        | :---                  | :---                | :---        | :---                                            |
| Students    | StudentID             | None                | 3NF         | Lưu trữ thông tin sinh viên                     |
| Professors  | ProfessorEmail        | None                | 3NF         | Lưu trữ thông tin giảng viên                    |
| Courses     | CourseID              | ProfessorEmail      | 3NF         | Lưu trữ thông tin môn học                       |
| Enrollments | (StudentID, CourseID) | StudentID, CourseID | 3NF         | Bảng trung gian liên kết sinh viên với khoá học |

## Task 2:
**Students - Courses**<br>
**Relationship Type:** N:N. Một sinh viên có thể đăng ký nhiều môn học và một môn học có thể có nhiều sinh viên

**Students - Enrollments**<br>
**Relationship Type:** 1:N. Một sinh viên có nhiều bảng liên kết do học nhiều môn

**Courses - Enrollments**<br>
**Relationship Type:** 1:N. Một môn có nhiều bảng liên kết do có nhiều sinh viên học

**Professors - Courses**<br>
**Relationship Type:** 1:N. Một giảng viên dạy nhiều môn học khác nhau

# Part 2: Relationships
**1. Author — Book**
- **Relationship Type:** N:N. Một cuốn sách có thể có nhiều tác giả và một tác giả có thể viết nhiều sách
- **FK Location:** Ở bảng trung gian

**2. Citizen — Passport**
- **Relationship Type:** 1:1. Một công dân chỉ được có một hộ chiếu và ngược lại
- **FK Location:** Ở bảng `Passport` có ràng buộc UNIQUE

**3. Customer — Order**
- **Relationship Type:** 1:N. Một khách hàng có thể tạo nhiều đơn hàng
- **FK Location:** Ở bảng `Order`

**4. Student — Class**
- **Relationship Type:** N:N. Một sinh viên có thê học nhiều lớp và một lớp có nhiều sinh viên
- **FK Location:** Ở bảng trung gian

**5. Team — Player**
- **Relationship Type:** 1:N. Một đội bóng có nhiều cầu thủ, nhưng một cầu thủ chỉ thuộc một đội
- **FK Location:** Ở bảng `Player`