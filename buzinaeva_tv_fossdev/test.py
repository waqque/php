from script import sum, divide
def test_sum():
    a = 1
    b = 2
    result = 3
    assert sum(a, b) == result
def test_divide():
    a = 2
    b = 4
    result = 0.5
    assert divide(a, b) == result
def test_division_prohibited():
    try:
        divide("A", "B")
        print("Test-string-division failed")
        assert False
    except ValueError as e:
        print("Test string-division passed")
def test_divide_zero():
    a = 2
    b = 0
    try:
        sum(a, b)
        assert False
    except ValueError as e:
        print("Test zero-division passed")
if __name__ == "__main__":
    test_divide()
    test_sum()
