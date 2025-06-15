package com.example.demo;

import javafx.application.Application;
import javafx.beans.value.ChangeListener;
import javafx.geometry.Pos;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Alert.AlertType;
import javafx.scene.control.Button;
import javafx.scene.control.ButtonType;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyCode;
import javafx.scene.layout.*;
import javafx.scene.text.Font;
import javafx.scene.text.Text;
import javafx.stage.Stage;
import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.util.Duration;

public class SudokuGame extends Application {
    private static final int SIZE = 9; // 定义数独的大小为9x9
    private TextField[][] cells = new TextField[SIZE][SIZE]; // 存储每个单元格的文本框
    private boolean[][] isEditable = new boolean[SIZE][SIZE]; // 存储每个单元格是否可编辑
    private SudokuGenerator generator = new SudokuGenerator(); // 数独生成器，用于生成游戏板
    private int secondsElapsed = 0; // 记录经过的秒数
    private Timeline timeline = new Timeline(); // 时间线，用于计时
    private Text timerText = new Text(); // 显示计时器文本
    private Button easyButton, mediumButton, hardButton, exitButton; // 游戏难度选择按钮和退出按钮

    @Override
    public void start(Stage primaryStage) {
        showCoverPage(primaryStage); // 显示封面页面
    }

    // 显示封面页面
    private void showCoverPage(Stage primaryStage) {
        StackPane root = new StackPane(); // 使用StackPane堆叠布局
        VBox content = new VBox(20); // 垂直布局，子节点之间间隔20
        content.setAlignment(Pos.CENTER); // 居中对齐

        // 使用类加载器加载封面图片
        Image coverImage = new Image(getClass().getResource("/image/cover_image.png").toExternalForm());
        BackgroundImage backgroundImage = new BackgroundImage(coverImage,
                BackgroundRepeat.NO_REPEAT, // 背景图片不重复
                BackgroundRepeat.NO_REPEAT,
                BackgroundPosition.CENTER, // 居中显示背景图片
                new BackgroundSize(BackgroundSize.AUTO, BackgroundSize.AUTO, false, false, true, false));
        Background background = new Background(backgroundImage);

        // 创建一个不可见的背景ImageView以防止图片影响游戏内容
        ImageView backgroundImageView = new ImageView(coverImage);
        backgroundImageView.setOpacity(0.3); // 设置图片透明度为30%

        // 创建标题标签
        Label title = new Label("Welcome to Sudoku Game");
        title.setFont(new Font(30)); // 设置标题字体大小

        // 创建"Start Game"按钮
        Button startButton = new Button("Start Game");
        startButton.setOnAction(e -> showMainMenu(primaryStage)); // 点击按钮时显示主菜单

        content.getChildren().addAll(title, startButton); // 将标题和按钮添加到垂直布局容器中

        root.setBackground(background); // 设置StackPane的背景为封面图片
        root.getChildren().addAll(content); // 将内容添加到StackPane中

        Scene scene = new Scene(root, 400, 700); // 创建场景，大小为400x700
        primaryStage.setScene(scene); // 设置舞台场景
        primaryStage.show(); // 显示舞台
    }

    // 显示主菜单
    private void showMainMenu(Stage primaryStage) {
        StackPane root = new StackPane(); // 使用StackPane堆叠布局
        VBox content = new VBox(20); // 垂直布局，子节点之间间隔20
        content.setAlignment(Pos.CENTER); // 居中对齐

        // 使用类加载器加载封面图片
        Image coverImage = new Image(getClass().getResource("/image/cover_image.png").toExternalForm());
        BackgroundImage backgroundImage = new BackgroundImage(coverImage,
                BackgroundRepeat.NO_REPEAT, // 背景图片不重复
                BackgroundRepeat.NO_REPEAT,
                BackgroundPosition.CENTER, // 居中显示背景图片
                new BackgroundSize(BackgroundSize.AUTO, BackgroundSize.AUTO, false, false, true, false));
        Background background = new Background(backgroundImage);

        // 创建标题标签
        Label title = new Label("Choose Level");
        title.setFont(new Font(36)); // 设置标题字体大小

        // 创建按钮
        easyButton = new Button("Easy");
        mediumButton = new Button("Medium");
        hardButton = new Button("Hard");
        exitButton = new Button("Exit");

        // 设置按钮点击事件
        easyButton.setOnAction(e -> startGame(primaryStage, "Easy"));
        mediumButton.setOnAction(e -> startGame(primaryStage, "Medium"));
        hardButton.setOnAction(e -> startGame(primaryStage, "Hard"));
        exitButton.setOnAction(e -> primaryStage.close()); // 点击退出按钮关闭程序

        // 将标题和按钮添加到垂直布局容器中
        content.getChildren().addAll(title, easyButton, mediumButton, hardButton, exitButton);

        // 设置StackPane的背景为封面图片
        root.setBackground(background);
        root.getChildren().addAll(content); // 将内容添加到StackPane中

        Scene scene = new Scene(root, 400, 700); // 创建场景，大小为400x700
        primaryStage.setScene(scene); // 设置舞台场景
        primaryStage.show(); // 显示舞台
    }

    // 开始游戏
    private void startGame(Stage primaryStage, String difficulty) {
        resetGame(); // 重置游戏状态

        GridPane grid = new GridPane(); // 创建网格布局
        int[][] board = generator.generateBoard(difficulty); // 生成游戏板

        for (int row = 0; row < SIZE; row++) {
            for (int col = 0; col < SIZE; col++) {
                cells[row][col] = new TextField();
                cells[row][col].setAlignment(Pos.CENTER); // 数字居中
                cells[row][col].setFont(new Font(20)); // 增大游戏内数字的字体

                if (board[row][col] != 0) { // 如果初始游戏板中的数字不为0
                    cells[row][col].setText(String.valueOf(board[row][col])); // 显示数字
                    cells[row][col].setEditable(false); // 设为不可编辑
                    isEditable[row][col] = false;

                    // 设置固定数字区域的背景色为蓝色
                    cells[row][col].setStyle("-fx-control-inner-background: lightblue;");
                } else {
                    isEditable[row][col] = true;

                    // 设置输入监听器，动态修改字体颜色为蓝色
                    final int r = row;
                    final int c = col;
                    cells[row][col].textProperty().addListener((observable, oldValue, newValue) -> {
                        if (!newValue.isEmpty() && !newValue.matches("\\d")) {
                            // 如果输入不是数字，将文本框重置为空
                            cells[r][c].setText("");
                        } else {
                            // 否则，设置字体颜色为蓝色
                            cells[r][c].setStyle("-fx-text-fill: blue;");
                        }
                    });
                }
                cells[row][col].setPrefHeight(50); // 设置单元格高度
                cells[row][col].setPrefWidth(50); // 设置单元格宽度

                // 添加九宫格边框
                String style = "-fx-border-color: black; -fx-border-width: ";
                if (row % 3 == 0) {
                    style += "3 1 1 1;"; // 上边框加粗
                } else if (row == SIZE - 1) {
                    style += "1 1 3 1;"; // 下边框加粗
                } else {
                    style += "1 1 1 1;";
                }

                if (col % 3 == 0) {
                    style = style.replaceFirst("1;", "3 1 1;"); // 左边框加粗
                } else if (col == SIZE - 1) {
                    style = style.replaceFirst("1;", "1 3 1;"); // 右边框加粗
                }

                cells[row][col].setStyle(style); // 应用样式

                grid.add(cells[row][col], col, row); // 将单元格添加到网格布局中

                // 监听回车键，检查游戏完成情况
                cells[row][col].setOnKeyPressed(event -> {
                    if (event.getCode() == KeyCode.ENTER) {
                        checkCompletion(primaryStage);
                    }
                });
            }
        }

        // 添加计时器和功能按钮
        timerText.setText("Time: 0");
        HBox controls = new HBox(10); // 水平布局，子节点之间间隔10
        controls.setAlignment(Pos.CENTER);
        Button returnToMenuButton = new Button("Back to Menu");
        Button exitButton = new Button("Exit");

        // 设置功能按钮点击事件
        returnToMenuButton.setOnAction(e -> {
            timeline.stop();
            showMainMenu(primaryStage);
        });
        exitButton.setOnAction(e -> primaryStage.close());

        controls.getChildren().addAll(timerText, returnToMenuButton, exitButton); // 添加控件到水平布局

        // 添加控件到网格布局的底部
        grid.add(controls, 0, SIZE, SIZE, 1);

        // 创建KeyFrame，每秒更新一次计时器
        KeyFrame keyFrame = new KeyFrame(Duration.seconds(1), e -> {
            secondsElapsed++;
            timerText.setText("Time: " + secondsElapsed);
        });
        timeline.getKeyFrames().add(keyFrame);
        timeline.setCycleCount(Timeline.INDEFINITE); // 无限循环
        timeline.play(); // 开始计时

        // 使用StackPane作为根节点，确保内容居中显示
        StackPane root = new StackPane();
        root.getChildren().add(grid);

        // 设置根节点居中显示
        root.setAlignment(Pos.CENTER);

        Scene scene = new Scene(root, 450, 500);
        primaryStage.setScene(scene);
        primaryStage.sizeToScene(); // 根据内容调整舞台大小

        // 监听窗口大小变化，确保内容始终居中显示
        ChangeListener<Number> stageSizeListener = (observable, oldValue, newValue) -> {
            grid.setTranslateX((primaryStage.getWidth() - grid.getWidth()) / 2);
            grid.setTranslateY((primaryStage.getHeight() - grid.getHeight()) / 2);
        };
        primaryStage.widthProperty().addListener(stageSizeListener);
        primaryStage.heightProperty().addListener(stageSizeListener);

        primaryStage.show();

        // 初始化位置
        grid.setTranslateX((primaryStage.getWidth() - grid.getWidth()) / 2);
        grid.setTranslateY((primaryStage.getHeight() - grid.getHeight()) / 2);
    }

    // 检查游戏完成情况
    private void checkCompletion(Stage primaryStage) {
        boolean hasErrors = false;
        if (isBoardComplete()) {
            if (isBoardCorrect()) {
                timeline.stop();
                showCompletionAlert(primaryStage, "You Win!", "Total Time: " + secondsElapsed + " 秒");
            } else {
                hasErrors = true;
                showAlert("There are errors", getErrorPositions());
            }
        } else {
            showAlert("Incomplete, please continue", getEmptyPositions());
        }

        if (hasErrors) {
            markErrors(); // 标记错误单元格
        }
    }

    // 标记错误的单元格
    private void markErrors() {
        for (int row = 0; row < SIZE; row++) {
            for (int col = 0; col < SIZE; col++) {
                String text = cells[row][col].getText();
                if (isEditable[row][col] && (!text.matches("\\d") || !isValid(row, col, Integer.parseInt(text)))) {
                    cells[row][col].setStyle("-fx-text-fill: red;"); // 将错误的数字标成红色
                } else if (isEditable[row][col]) {
                    cells[row][col].setStyle("-fx-text-fill: blue;"); // 正确的输入保持蓝色
                }
            }
        }
    }

    // 检查游戏板是否填满
    private boolean isBoardComplete() {
        for (int row = 0; row < SIZE; row++) {
            for (int col = 0; col < SIZE; col++) {
                if (cells[row][col].getText().isEmpty()) {
                    return false;
                }
            }
        }
        return true;
    }

    // 检查游戏板是否正确
    private boolean isBoardCorrect() {
        for (int row = 0; row < SIZE; row++) {
            for (int col = 0; col < SIZE; col++) {
                String text = cells[row][col].getText();
                if (!text.matches("\\d") || !isValid(row, col, Integer.parseInt(text))) {
                    return false;
                }
            }
        }
        return true;
    }

    // 检查数字在当前行、列和3x3小格中是否有效
    private boolean isValid(int row, int col, int number) {
        // 检查行
        for (int c = 0; c < SIZE; c++) {
            if (c != col && cells[row][c].getText().equals(String.valueOf(number))) {
                return false;
            }
        }
        // 检查列
        for (int r = 0; r < SIZE; r++) {
            if (r != row && cells[r][col].getText().equals(String.valueOf(number))) {
                return false;
            }
        }
        // 检查3x3小格
        int boxRowStart = (row / 3) * 3;
        int boxColStart = (col / 3) * 3;
        for (int r = boxRowStart; r < boxRowStart + 3; r++) {
            for (int c = boxColStart; c < boxColStart + 3; c++) {
                if ((r != row || c != col) && cells[r][c].getText().equals(String.valueOf(number))) {
                    return false;
                }
            }
        }
        return true;
    }

    // 获取错误单元格位置
    private String getErrorPositions() {
        StringBuilder errors = new StringBuilder("Error positions: ");
        for (int row = 0; row < SIZE; row++) {
            for (int col = 0; col < SIZE; col++) {
                String text = cells[row][col].getText();
                if (isEditable[row][col] && (!text.matches("\\d") || !isValid(row, col, Integer.parseInt(text)))) {
                    errors.append(String.format("(%d, %d) ", row + 1, col + 1));
                }
            }
        }
        return errors.toString();
    }

    // 获取未填单元格位置
    private String getEmptyPositions() {
        StringBuilder empties = new StringBuilder("Incomplete cells: ");
        for (int row = 0; row < SIZE; row++) {
            for (int col = 0; col < SIZE; col++) {
                if (cells[row][col].getText().isEmpty()) {
                    empties.append(String.format("(%d, %d) ", row + 1, col + 1));
                }
            }
        }
        return empties.toString();
    }

    // 显示提示框
    private void showAlert(String title, String message) {
        Alert alert = new Alert(AlertType.INFORMATION);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }

    // 显示游戏完成提示框
    private void showCompletionAlert(Stage primaryStage, String title, String message) {
        Alert alert = new Alert(AlertType.INFORMATION);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);

        ButtonType returnToMenu = new ButtonType("Back to Menu");
        ButtonType exit = new ButtonType("退出");
        alert.getButtonTypes().setAll(returnToMenu, exit);

        alert.showAndWait().ifPresent(type -> {
            if (type == returnToMenu) {
                resetGame();
                showMainMenu(primaryStage);
            } else if (type == exit) {
                primaryStage.close();
            }
        });
    }

    // 重置游戏状态
    private void resetGame() {
        secondsElapsed = 0; // 重置计时器
        timeline.stop(); // 停止时间线
        timeline.getKeyFrames().clear(); // 清除时间线关键帧
    }

    public static void main(String[] args) {
        launch(args); // 启动JavaFX应用程序
    }
}
