package com.example.demo;

public class SudokuGenerator {
    private int[][] board;
    private static final int[][] EASY_BOARD = {
            {1, 3, 6, 8, 2, 4, 5, 9, 7},
            {7, 0, 9, 6, 3, 5, 2, 1, 8},
            {2, 0, 8, 7, 9, 1, 6, 3, 4},
            {0, 8, 5, 9, 7, 6, 3, 2, 1},
            {3, 0, 1, 5, 4, 8, 7, 6, 9},
            {9, 6, 0, 3, 1, 2, 8, 0, 5},
            {6, 0, 4, 1, 8, 0, 9, 5, 2},
            {5, 9, 2, 4, 6, 7, 1, 8, 3},
            {0, 1, 3, 0, 5, 9, 4, 7, 6}
    };

    private static final int[][] MEDIUM_BOARD = {
            {8, 1, 4, 0, 2, 3, 5, 6, 0},
            {0, 0, 2, 1, 4, 0, 7, 8, 3},
            {0, 0, 3, 0, 5, 6, 0, 2, 4},
            {5, 8, 0, 0, 3, 7, 4, 1, 2},
            {0, 3, 0, 0, 0, 0, 6, 5, 8},
            {0, 0, 1, 4, 8, 0, 9, 0, 7},
            {7, 9, 8, 2, 6, 1, 3, 4, 5},
            {0, 4, 5, 3, 9, 8, 0, 0, 6},
            {3, 2, 0, 5, 7, 4, 8, 9, 0}
    };

    private static final int[][] HARD_BOARD = {
            {0, 5, 0, 0, 0, 0, 4, 0, 2},
            {4, 2, 0, 3, 0, 1, 0, 8, 6},
            {1, 8, 6, 0, 2, 0, 9, 0, 0},
            {0, 0, 1, 0, 0, 0, 0, 0, 4},
            {0, 9, 0, 0, 0, 8, 7, 5, 0},
            {8, 7, 5, 9, 0, 3, 2, 0, 0},
            {0, 6, 0, 7, 5, 4, 0, 2, 0},
            {0, 0, 8, 0, 6, 2, 3, 7, 0},
            {0, 1, 2, 8, 0, 9, 0, 0, 5}
    };

    public int[][] generateBoard(String difficulty) {
        switch (difficulty) {
            case "Easy":
                return EASY_BOARD;
            case "Medium":
                return MEDIUM_BOARD;
            case "Hard":
                return HARD_BOARD;
            default:
                return EASY_BOARD;
        }
    }
}
